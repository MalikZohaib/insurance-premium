<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Controller;

use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Zohaibdev\InsurnacePremium\Domain\Model\Dto\PolicySearch;
use Zohaibdev\InsurnacePremium\Domain\Repository\InsurancePoliciesRepository;
use Zohaibdev\InsurnacePremium\Service\AgeRangeResolver;
use Zohaibdev\InsurnacePremium\Utility\JsonResponseFactory;

class InsuranceController extends ActionController
{

    /**
     * @var InsurancePoliciesRepository
     */
    protected InsurancePoliciesRepository $insurancePoliciesRepository;

    /**
     * Inject the InsurancePoliciesRepository
     */
    public function injectInsurancePoliciesRepository(InsurancePoliciesRepository $insurancePoliciesRepository): void
    {
        $this->insurancePoliciesRepository = $insurancePoliciesRepository;
    }

    /**
     * Show the form with the configured insurance policy
     */
    public function showCalculatorAction(?PolicySearch $policySearch = null): ResponseInterface
    {
        // If no policySearch object is provided, create a new one
        if ($policySearch === null) {
            $policySearch = GeneralUtility::makeInstance(PolicySearch::class);
        }

        $policyId = (int) ($this->settings['policy'] ?? 0);

        // If a policy ID is provided in the settings, fetch the policy data
        $policyContribution = $this->insurancePoliciesRepository->findByUid($policyId);
        if ($policyContribution === null) {
            // If no policy is found, return an error response
            $this->addFlashMessage('No policy found with the given ID.', '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
            return $this->htmlResponse();
        }

        $policySearch->setPolicyUid($policyId);

        // Assign the policy contribution to the view
        $this->view->assign('policyContributionTitle', $policyContribution->getTitle());
        $this->view->assign('policySearch', $policySearch);
        return $this->htmlResponse();
    }

    /**
     * Handle the AJAX request to calculate the contribution based on age
     *
     * @param PolicySearch $policySearch
     * @return JsonResponse
     */
    public function ajaxAction(PolicySearch $policySearch): ResponseInterface
    {

        $policyContribution = $this->insurancePoliciesRepository->findByUid($policySearch->getPolicyUid());
        if ($policyContribution === null) {
            return JsonResponseFactory::error('No policy found with the given ID', 404);
        }

        // find the contribution based on the age inside the policy body
        $resolver = new AgeRangeResolver($policyContribution->getBody());
        $contribution = $resolver->findContributionByAge((int) $policySearch->getAge());
        if ($contribution === null) {
            return JsonResponseFactory::error('No contribution found for the given age', 404);
        }

        // Return the contribution as a JSON response
        return JsonResponseFactory::success([
            'age' => $policySearch->getAge(),
            'contribution' => $contribution,
        ]);
    }

    /**
     * errorAjaxAction
     * 
     * This action is used to handle errors in AJAX requests.
     * 
     */
    public function errorAction(): ResponseInterface
    {
        if ($this->request->getControllerActionName() === 'ajax') {
            return JsonResponseFactory::validationError($this->collectValidationErrors());
        }

        return parent::errorAction();
    }

    protected function collectValidationErrors(): array
    {
        $errors = [];

        foreach ($this->arguments as $argument) {
            if (!$argument->getValidationResults()->hasErrors()) {
                continue;
            }

            $argName = $argument->getName();
            $errors[$argName] = [];

            $flattened = $argument->getValidationResults()->getFlattenedErrors();

            foreach ($flattened as $property => $propertyErrors) {
                $errors[$argName][$property] = [];

                foreach ($propertyErrors as $error) {
                    $message = $error->getMessage();

                    // Optional: Replace raw placeholders like "%s"
                    $message = str_replace('%s', 'value', $message);
                    $errors[$argName][$property][] = $message;
                }
            }
        }

        return $errors;
    }

}