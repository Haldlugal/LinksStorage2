<?php


class ElementsController extends CommonController
{
    protected function getPagination($elements) {
        $page = $_GET["page"];
        $elementsOnPage = $_GET["elementsOnPage"];

        if ($page == "") {
            $page = 1;
        }
        if ($elementsOnPage == "") {
            $elementsOnPage = ServiceProvider::getService("Config")->getPaginationCount();
        }
        $pagination = ServiceProvider::getService("Pagination")->generatePagination(count($elements), $elementsOnPage, $page);
        return $pagination;
    }

    protected function getElementsToShow($elements) {
        $page = $_GET["page"];
        $elementsOnPage = $_GET["elementsOnPage"];

        if ($page == "") {
            $page = 1;
        }
        if ($elementsOnPage == "") {
            $elementsOnPage = ServiceProvider::getService("Config")->getPaginationCount();
        }
        return ServiceProvider::getService("Pagination")->getElementsToShow($elements, $elementsOnPage, $page);
    }
}