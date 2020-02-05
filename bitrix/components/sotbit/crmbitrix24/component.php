<?
use Bitrix\Main\Config\Option;
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

try
{
    // check include module
    if( !\Bitrix\Main\Loader::includeModule( "sotbit.crmbitrix24" ) )
        throw new Exception( 'Can not include module sotbit.crmbitrix24' );
    
    // check event
    if( !\CSotbitCRM::CheckCrmEvent( $arParams['EVENT'] ) && strcmp( $arParams['EVENT'], 'ONCRMINVOICESETSTATUS' ) != 0 )
        throw new Exception( 'Event "' . $arParams['EVENT'] . '"  invalid' );
    
    $siteID = \CSotbitCRM::GetSitesByCrmClienPoint( $arParams['AUTH'] );
    
    if($siteID == false) {
        return;
    }
    
    $entity = false;
    // get Class by event
    
    $event = $arParams['EVENT'];
    $authToken = $arParams['AUTH']['application_token'];
    $typeWay = Option::get('sotbit.crmbitrix24','UPLOAD_TYPE','',$siteID);
    
    if(
        \CSotbitCRM::isDealWay($typeWay, $event, $authToken, $siteID)
        || \CSotbitCRM::isInvoiceWay($typeWay, $event, $authToken, $siteID)
        || \CSotbitCRM::isProductWay($event, $authToken, $siteID)
        || \CSotbitCRM::isContactWay($event, $authToken, $siteID)
        || \CSotbitCRM::isCompanyWay($event, $authToken, $siteID)
    )
    {
        $entity = \CSotbitCRM::GetEntityFromEvent( $arParams['EVENT'] );
    }
    
    // here need add check auth code
    if( $entity === false )
        throw new Exception( 'Do not have Instance for event "' . $arParams['EVENT'] . '"' );
    
    // check method for Queue
    if( !method_exists( $entity, 'QueuePush' ) ) {
        throw new Exception('Class ' . $entity . ' does not have method "QueuePush"');
    }
    
    if( strcmp( $arParams['EVENT'], 'ONCRMINVOICESETSTATUS' ) != 0 )
        $action = \CSotbitCRM::GetActionFromEvent( $arParams['EVENT'] );
    else
        $action = 'SetStatus';
    
    if( $siteID === false )
        throw new \Exception( 'Not found settings for connection "' . $arParams['AUTH']['client_endpoint'] . '"' );
    
    $result = $entity::QueuePush(array('ID' => intval($arParams['DATA']['FIELDS']['ID']), 'action' => $action, 'SITE_ID' => $siteID));
}
catch ( Exception $e )
{
    $context = \Bitrix\Main\Application::getInstance()->getContext();
    $request = $context->getRequest();
    $page = $request->getRequestedPage();
    CEventLog::Add( array(
        'SEVERITY' => 'ERROR',
        'AUDIT_TYPE_ID' => $page,
        'MODULE_ID' => 'sotbit.crmbitrix24',
        'ITEM_ID' => 'CRM API',
        'DESCRIPTION' => $e->getMessage()
    ) );
    die();
}
?>