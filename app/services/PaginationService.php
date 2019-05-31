<?php


class PaginationService
{
    public function generatePagination($numberOfElements, $elementsOnPage, $currentPage) {
        if ($numberOfElements >= $elementsOnPage) {
            if ($currentPage == "") {
                $currentPage = 1;
            }
            if ($elementsOnPage == "") {
                $elementsOnPage = ServiceProvider::getService("Config")->getPaginationCount();
            }
            if ($currentPage == 1) {
                $previous = 0;
            } else {
                $previous = $currentPage - 1;
            }
            if ($numberOfElements > $elementsOnPage * $currentPage) {
                $next = $currentPage + 1;
            } else {
                $next = 0;
            }
            $paginationString = '
            <nav>
                <ul class="pagination  mt-4 px-5">        
        ';

            if ($previous != 0) {
                $paginationString .= '
                <li class="page-item">
                    <a class="page-link" href="?page=' . $previous . '&elementsOnPage=' . $elementsOnPage . $this->generateGetUrlPart(). '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            ';
            }

            for ($i = 1; $i < $numberOfElements / $elementsOnPage + 1; $i++) {
                if ($i == $currentPage) {
                    $active = "active";
                } else $active = "";
                $paginationString .= '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '&elementsOnPage=' . $elementsOnPage . $this->generateGetUrlPart(). '">' . $i . '</a></li>';
            }

            if ($next != 0) {
                $paginationString .= '<li class="page-item">
                    <a class="page-link" href="?page=' . $next . '&elementsOnPage=' . $elementsOnPage . $this->generateGetUrlPart(). '" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>';
            }

            $paginationString .= '</ul></nav>';
        }
        else {
            $paginationString = "";
        }

        return $paginationString;
    }

    public function getElementsToShow($elements, $elementsOnPage, $page) {
        return array_slice($elements, ($page-1)*$elementsOnPage, $elementsOnPage);
    }

    private function generateGetUrlPart(){
        $string ="";
        foreach ($_GET as $key=>$value){
            if ($key!="page" && $key!="elementsOnPage") {
                $string.="&$key=$value";
            }
        }
        return $string;
    }
}