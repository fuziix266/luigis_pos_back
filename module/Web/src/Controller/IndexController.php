<?php

namespace Web\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * Página principal - Carta Digital Luigi's Pizza
     */
    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTerminal(false);
        return $view;
    }

    /**
     * Listado de Pizzas
     */
    public function pizzasAction()
    {
        return new ViewModel();
    }

    /**
     * Promociones
     */
    public function promocionesAction()
    {
        return new ViewModel();
    }

    /**
     * Arma tu Pizza
     */
    public function armaTuPizzaAction()
    {
        return new ViewModel();
    }

    /**
     * Ingredientes Extra
     */
    public function ingredientesExtraAction()
    {
        return new ViewModel();
    }

    /**
     * Bebidas y Otros
     */
    public function bebidasAction()
    {
        return new ViewModel();
    }

    /**
     * Promo del Día - detecta el día automáticamente o usa parámetro
     */
    public function promoDiaAction()
    {
        $dia = $this->params()->fromRoute('dia', null);

        if (!$dia) {
            // Detectar día actual
            $dias = [
                0 => 'domingo',
                1 => 'lunes',
                2 => 'martes',
                3 => 'miercoles',
                4 => 'jueves',
                5 => 'viernes',
                6 => 'sabado',
            ];
            $dia = $dias[(int) date('w')];
        }

        $nombres = [
            'lunes'     => 'Lunes',
            'martes'    => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves'    => 'Jueves',
            'viernes'   => 'Viernes',
            'sabado'    => 'Sábado',
            'domingo'   => 'Domingo',
        ];

        return new ViewModel([
            'dia'       => $dia,
            'nombreDia' => $nombres[$dia] ?? ucfirst($dia),
        ]);
    }
}
