<?php

namespace Catalog\Controller;

use Catalog\Service\CatalogService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class CatalogController extends AbstractActionController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function updatePricesAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);
        
        $request = $this->getRequest();
        if (!$request->isPost() && !$request->isPut()) {
            return new JsonModel(['success' => false, 'error' => 'Method not allowed']);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['updates'])) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(['success' => false, 'error' => 'Invalid data format']);
        }

        try {
            $this->catalogService->updatePrices($data['updates']);
            return new JsonModel(['success' => true]);
        } catch (\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function pizzasAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);

        $id = $this->params()->fromRoute('id');

        if ($id) {
            $pizza = $this->catalogService->getPizzaById((int) $id);
            if (!$pizza) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(['success' => false, 'error' => 'Pizza no encontrada']);
            }
            return new JsonModel(['success' => true, 'data' => $pizza]);
        }

        return new JsonModel([
            'success' => true,
            'data' => $this->catalogService->getAllPizzas(),
        ]);
    }

    public function ingredientsAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->catalogService->getAllIngredients(),
        ]);
    }

    public function drinksAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->catalogService->getAllDrinks(),
        ]);
    }

    public function sidesAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->catalogService->getAllSides(),
        ]);
    }

    public function sizesAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);

        return new JsonModel([
            'success' => true,
            'data' => $this->catalogService->getAllSizes(),
        ]);
    }

    public function promosAction(): JsonModel
    {
        if ($this->getRequest()->isOptions()) return new JsonModel([]);

        $action = $this->params()->fromRoute('action', 'promos');

        if ($action === 'today') {
            return new JsonModel([
                'success' => true,
                'data' => $this->catalogService->getPromoToday(),
            ]);
        }

        return new JsonModel([
            'success' => true,
            'data' => $this->catalogService->getAllPromos(),
        ]);
    }
}
