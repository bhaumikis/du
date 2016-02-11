<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * cardsModel Class
 */
class cardsModel extends globalModel {

    /**
     * get all card of user
     * @return type
     */
    function getCardListByUserId() {
        $rsResult = $this->getDBTable("cards")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));

        foreach ($rsResult as $arrData) {
            $arrFinal[$arrData['card_id']] = $arrData['title'];
        }

        return $arrFinal;
    }

}
