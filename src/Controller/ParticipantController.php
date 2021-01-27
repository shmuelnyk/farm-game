<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\QuizAnswer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/api/participants/search", name="participants_search", methods={"GET"})
     */
    public function participants(Request $request)
    {
        dump('her');exit;
        $em = $this->getDoctrine()->getManager();
        $params = json_decode($request->get('form'),true);
        $participant = new Participant();
        $em->persist($participant);
        foreach ($params as $test => $answers){
            foreach ($answers as $answer){
                $quizAnswer = new QuizAnswer();
                $quizAnswer->setTest($answer['quizName']);
                $quizAnswer->setOptionOne($answer['option1']);
                $quizAnswer->setOptionTwo($answer['option2']);
                $quizAnswer->setAnswer($answer['answer']);
                $em->persist($quizAnswer);
                $participant->addQuizAnswer($quizAnswer);
                $em->persist($participant);
            }
        }
        $em->flush();

        return new JsonResponse(null,200);
    }

    /**
    * @Route("/api/participants/export", name="participants_export", methods="POST")
    */
    public function exportParticipants(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $columns = array(
            'Quiz ID',
            'Test Name',
            'Option one',
            'Option two',
            'Answer',

        );
        $participants = $em->getRepository(Participant::class)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();;
        $column = 'A';
        $row = 1;
        foreach ($columns as $columnName) {
            $sheet->setCellValue($column . $row, $columnName);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }
        $row++;
        $date = new \DateTime();
        $sheet->setTitle("Participants " . $date->format('d J Y'));
        foreach ($participants as $result) {
            foreach($result->getQuizAnswers() as $answer){
                $column = 'A';
                $sheet->setCellValue($column . $row, $result->getId());
                $column++;
                $sheet->setCellValue($column . $row, $answer->getTest());
                $column++;
                $sheet->setCellValue($column . $row, $answer->getOptionOne());
                $column++;
                $sheet->setCellValue($column . $row, $answer->getOptionTwo());
                $column++;
                $sheet->setCellValue($column . $row, $answer->getAnswer());
                $row++;
            }
            $row++;
            $row++;

        }
        $writer = new Xlsx($spreadsheet);

        ob_start();
        $writer->save("php://output");
        $file = ob_get_contents();
        ob_end_clean();
        return new JsonResponse(array(
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($file),
        ));
    }
}
