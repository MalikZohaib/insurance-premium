<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Controller;

use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Zohaibdev\InsurnacePremium\Domain\Model\Dto\PolicySearch;
use Zohaibdev\InsurnacePremium\Domain\Repository\InsurancePoliciesRepository;
use Zohaibdev\InsurnacePremium\Service\AgeContributionCacheService;
use Zohaibdev\InsurnacePremium\Service\AgeRangeResolver;
use Zohaibdev\InsurnacePremium\Utility\JsonResponseFactory;

class InsuranceController extends ActionController
{

    /**
     * @var InsurancePoliciesRepository
     */
    protected InsurancePoliciesRepository $insurancePoliciesRepository;

    protected AgeContributionCacheService $ageContributionCacheService;

    /**
     * Inject the InsurancePoliciesRepository
     */
    public function injectInsurancePoliciesRepository(InsurancePoliciesRepository $insurancePoliciesRepository): void
    {
        $this->insurancePoliciesRepository = $insurancePoliciesRepository;
    }

    /**
     * Inject the AgeContributionCacheService
     */
    public function injectAgeContributionCacheService(AgeContributionCacheService $ageContributionCacheService): void
    {
        $this->ageContributionCacheService = $ageContributionCacheService;
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
            $this->addFlashMessage(LocalizationUtility::translate('Insurancecalculator.ajax.no_policy_id', 'insurnace_premium'), '', \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR);
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
        // Check if policy is in the cache
        $cachedContribution = $this->ageContributionCacheService->getCache($policySearch);
        if ($cachedContribution !== null) {
            // If cached contribution exists, return it
            return JsonResponseFactory::success([
                'age' => $policySearch->getAge(),
                'contribution' => $cachedContribution,
            ]);
        }
        
        $policyContribution = $this->insurancePoliciesRepository->findByUid($policySearch->getPolicyUid());
        if ($policyContribution === null) {
            return JsonResponseFactory::error(LocalizationUtility::translate('Insurancecalculator.ajax.no_policy_id', 'insurnace_premium'), 404);
        }

        // find the contribution based on the age inside the policy body
        $resolver = new AgeRangeResolver($policyContribution->getBody());
        $contribution = $resolver->findContributionByAge((int) $policySearch->getAge());
        if ($contribution === null) {
            return JsonResponseFactory::error(LocalizationUtility::translate('Insurancecalculator.ajax.contribution_not_found', 'insurnace_premium'), 404);
        }

        // Set the contribution in the cache
        $this->ageContributionCacheService->setCache($policySearch, $contribution);

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