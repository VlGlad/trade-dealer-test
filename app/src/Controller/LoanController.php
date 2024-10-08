<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\LoanRequest;
use App\Form\Type\LoanRequestType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


class LoanController extends AbstractController
{
    #[Route('/api/v1/credit/calculate', name: 'app_loan', methods: ["GET"])]
    public function getCalculation(Request $request, EntityManagerInterface $em): Response
    {
        $data = $this->validate($request);

        if (gettype($data) == "string"){
            return $this->json($data, 422);
        }

        $program = $this->getProgram($data, $em);

        $monthlyPayment = $this->getMinimalPayment($data["price"], $data["initialPayment"], $data["loanTerm"], $program->getInterestRate());

        return $this->json([
            'programId' => $program->getId(),
            'interestRate' => $program->getInterestRate(),
            'title' => $program->getTitle(),
            'monthlyPayment' => $monthlyPayment
        ]);
    }

    #[Route('/api/v1/request', name: 'app_loan_request', methods: ["POST"])]
    public function saveRequest(Request $request, EntityManagerInterface $em): Response
    {
        $loanRequest = new LoanRequest();

        $form = $this->createForm(LoanRequestType::class, $loanRequest);

        $json = $request->getContent();
        if ($decodedJson = json_decode($json, true)) {
            $data = $decodedJson;
        } else {
            $data = $request->request->all();
        }
        $formData = [];
        foreach ($form->all() as $name => $field) {
            if (isset($data[$name])) {
                $formData[$name] = $data[$name];
            }
        }

        $form->submit($formData);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $loanRequest = $form->getData();

            $em->persist($loanRequest);
            $em->flush();

            return $this->json("success");
        }

        return $this->json("Invalid arguments", 422);
    }

    private function validate($request){
        $price = $request->query->get('price');
        $initialPayment = $request->query->get('initialPayment');
        $loanTerm = $request->query->get('loanTerm');

        if ((!(is_numeric($initialPayment) && floatval($initialPayment) >= 0))){
            return "Invalid initialPayment field";
        }

        if (!(is_numeric($price) && intval($price) > 0 && intval($price) - floatval($initialPayment) > 0)){
            return "Invalid price field";
        }
        
        if (!(is_numeric($loanTerm) && intval($loanTerm) > 0)){
            return "Invalid loanTerm field";
        }
        return [
            "price" => intval($price),
            "initialPayment" => round(floatval($initialPayment), 2),
            "loanTerm" => intval($loanTerm)
        ];
    }

    private function getMinimalPayment($credit, $initialPayment, $loanTerm, $percent): float
    {
        $rate = $percent / 100;
        $minimalPayment = ($credit - $initialPayment) * $rate / (12 * (1 - (1 + $rate / 12) ** -$loanTerm));
        $i = 10 ** (strlen($minimalPayment) - 2);
        return round(($minimalPayment / $i) * $i, 2);
    }

    private function getProgram($data, $em){
        // Выбор программы будет зависеть от размера кредита
        $credit = $data["price"] - $data["initialPayment"];
        switch ($credit) {
            case $credit < 200000:
                $program = $em->getRepository(Loan::class)->findOneBy(['id' => 1]);
                break;
            case $credit >= 200000 && $credit < 1000000:
                $program = $em->getRepository(Loan::class)->findOneBy(['id' => 2]);
                break;
            default:
                $program = $em->getRepository(Loan::class)->findOneBy(['id' => 3]);
                break;
        }
        return $program;
    }
}
