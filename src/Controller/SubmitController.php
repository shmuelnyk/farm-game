<?php

namespace App\Controller;

use App\Entity\AdminOption;
use App\Entity\Participant;
use App\Entity\QuizAnswer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubmitController extends AbstractController
{
    /**
     * @Route("/api/submit/quiz", name="submit_quiz", methods={"POST"})
     */
    public function submitQuiz(Request $request)
    {
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
        $options = $em->getRepository(AdminOption::class)->findOneBy(array('optionKey'=>'pageLink'));
        $link = null;
        if($options){
            $link = $options->getOptionValue();
        }

        return new JsonResponse(array('link'=>$link),200);
    }
}
