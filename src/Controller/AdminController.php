<?php

namespace App\Controller;

use App\Entity\AdminOption;
use App\Entity\Participant;
use App\Entity\QuizAnswer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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
        $data = json_decode($request->getContent(),true);
        $start = date_create_from_format('d-m-Y',$data['start']);
        $start->setTime(0,0,0,0);
        $end = date_create_from_format('d-m-Y',$data['end']);
        $end ->setTime(23,59);
        $participants = $em->getRepository(Participant::class)->createQueryBuilder('p')
            ->andwhere('p.createdAt >= :start')
            ->andwhere('p.createdAt <= :end')
            ->setParameter('start',$start)
            ->setParameter('end',$end)
            ->getQuery()
            ->getResult();

        if(count($participants) == 0){
            return new JsonResponse(array(
                'msg'=>'No participants for the selected range.'
            ),500);
        }
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
        $sheet->setTitle("Participants export");
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
            'count' => count($participants),
        ));
    }

    /**
     * @Route("/api/save/link", name="save_link", methods="POST")
     */
    public function saveLink(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $link = $request->get('link');
        $option = $em->getRepository(AdminOption::class)->findOneBy(array('optionKey'=>'pageLink'));
        if($option){
            $option->setOptionValue($link);
            $em->persist($option);
            $em->flush();
        }else{
            $option = new AdminOption();
            $option->setOptionValue($link);
            $option->setOptionKey('pageLink');
            $em->persist($option);
            $em->flush();
        }
        return new JsonResponse(null,200);
    }

    /**
     * @Route("/api/get/link", name="get_link", methods="GET")
     */
    public function getLink(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $link = null;
        $option = $em->getRepository(AdminOption::class)->findOneBy(array('optionKey'=>'pageLink'));
        if($option){
            $link = $option->getOptionValue();
        }else{
            $option = new AdminOption();
            $option->setOptionKey('pageLink');
            $em->persist($option);
            $em->flush();
        }
        return new JsonResponse(array('link'=>$link),200);
    }
}
