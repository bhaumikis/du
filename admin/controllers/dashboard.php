<?php

namespace admin\controllers;

/**
  \brief Index controllers contains actions for home page.
 */
class dashboardController extends adminGlobalController {

    function indexAction() {

        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/nv.d3.css")); //VISITOR CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/d3.v2.js")); //VISITOR CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/nv.d3.js")); //VISITOR CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/live-updating-chart.js")); //VISITOR CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.easypiechart.min.js")); //Easy Pie Chart
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/easy-pie-chart.js")); //Easy Pie Chart
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/pie-chart.js")); //PIE CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/doughnut.js")); //PIE CHART
        //Live Updating Chart
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/header-line-chart.js")); //PIE CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.flot.min.js")); //PIE CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/flot-chart-header.js")); //PIE CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/flot-jquery-header.js")); //PIE CHART
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/chart-line-and-graph.js")); //PIE CHART
    }

}
