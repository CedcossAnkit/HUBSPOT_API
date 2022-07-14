<?php

namespace App\Hubspotremote\Controllers;

use App\Connector\Components\Helper;
use AWS\CRT\HTTP\Response;
use Type;

class AssoController extends \App\Core\Controllers\BaseController
{

    //Genral Function of Common Purpose
    ///--------------------------------------------------------Code Optiomisation--------------------------------///

    //Create Association with Object
    public function createAssociationWithObjectAction()
    {
        if ($this->request->getPost('primaryObject')) {
            // return "Sucessfull";
            $primaryObject = $this->request->getPost('primaryObject');
            $primaryObjectID = $this->request->getPost('primaryObjectID');
            $AssoObjectID = $this->request->getPost('associatedObjectID');
            $AsssObject_type = $this->request->getPost('associatedObjectType');
            $associationType = $this->request->getPost('associationType');
            $helper = new HelperController();
            $response = $helper->curlPut("crm/v3/objects/" . $primaryObject . "/" . $primaryObjectID . "/associations/" . $AsssObject_type . "/" . $AssoObjectID . "/" . $associationType . "");
            return json_encode($response, true);
        } else {
            return "Association unsucessfull";
        }
    }

    //Remove Association with Object
    public function removeAssociationWithObjectAction()
    {
        if ($this->request->getPost('primaryObject')) {
            // return "Sucessfull";
            $primaryObject = $this->request->getPost('primaryObject');
            $primaryObjectID = $this->request->getPost('primaryObjectID');
            $AssoObjectID = $this->request->getPost('associatedObjectID');
            $AsssObject_type = $this->request->getPost('associatedObjectType');
            $associationType = $this->request->getPost('associationType');
            $helper = new HelperController();
            $response = $helper->curlDelete("crm/v3/objects/" . $primaryObject . "/" . $primaryObjectID . "/associations/" . $AsssObject_type . "/" . $AssoObjectID . "/" . $associationType . "");
            return json_encode($response, true);
        } else {
            return "Association unsucessfull";
        }
    }

    //Function for Listing the Assocaiton between two Objects
    public function getAssociatedIbjectsDetailsAction()
    {
        if ($this->request->isPost('contact_id')) {
            $helper = new HelperController();
            $primaryObject = $this->request->getPost('primaryObject');
            $primaryObjectID = $this->request->getPost('primaryObjectID');
            $toObjectType = $this->request->getPost('toObjectType');
            $response = $helper->curlGet("crm/v3/objects/" . $primaryObject . "/" . $primaryObjectID . "/associations/" . $toObjectType . "?limit=500");
            return json_encode($response, true);
        }
    }
    //----------------------------------------------------------;)----------------------------------------------------------------------------


    public function contactTocompanyAssoAction()
    {
        //Here you can create and remove Association between Company and Contacts
    }


    //Deal Association (Deal Main Page where you can create or remove association with another object)
    public function dealAction()
    {
        $helper = new HelperController();
        $contactData = $helper->curlGet('crm/v3/objects/deals?limit=10&archived=false');
        echo "<pre>";
        $HtmlContainstr = "";
        foreach ($contactData['results'] as $key => $value) {
            $HtmlContainstr .= '<option value=' . $value['id'] . '>' . $value['properties']['dealname'] . '</option>';
        }
        $this->view->data = $HtmlContainstr;
    }

    //Just Only For Testing Purpose
    public function testingAction()
    {
        $helper = new HelperController();
        $contactData = $helper->curlGet('crm/v3/objects/contacts?limit=100&archived=false');

        if ($this->request->isPost('SearchData')) {
            return json_encode($contactData, true);
        }
    }

    public function testing2Action()
    {
        if ($this->request->isPost('data')) {
            return "Yaa Data recived";
        }
    }



    //-------------------------------Fetch Details-->Function()----------------------------------------//

    //contact Details
    public function contactDeailsAction()
    {
        $helper = new HelperController();
        $contactData = $helper->curlGet('crm/v3/objects/contacts?limit=100&archived=false');
        return json_encode($contactData, true);
    }

    //One Contact Detail Fetch Fucntion
    public function contactDeatilsAction()
    {
        if ($this->request->getPost("contact_id")) {

            $Contact_id = $this->request->getPost("contact_id");
            $helper = new HelperController();
            $ArrayData = $helper->curlGet("crm/v3/objects/contacts/" . $Contact_id);
            return json_encode($ArrayData, true);
        } else {
            return "data Invalid Please Provide a contact_id";
        }
    }

    //Deal Details
    public function DealDetailsAction()
    {
        $helper = new HelperController();
        $contactData = $helper->curlGet('crm/v3/objects/deals?limit=10&archived=false');
        return $contactData;
    }

    //Companty Details
    public function companyDeatilsAction()
    {
        $helper = new HelperController();
        $ArrayData = $helper->curlGet("crm/v3/objects/companies?limit=100&archived=false");
        return json_encode($ArrayData);
    }

    //Filter Search and fetch details
    public function getSpacificContactDataAction($contact_id)
    {
        $helper = new HelperController();
        $jayParsedAry = [
            "filterGroups" => [
                [
                    "filters" => [
                        [
                            "propertyName" => "hs_object_id",
                            "operator" => "EQ",
                            "value" => $contact_id
                        ]
                    ]
                ]
            ]
        ];
        $ArrayData = $helper->curlPost("crm/v3/objects/contacts/search", $jayParsedAry);
        return $ArrayData;
    }
}
