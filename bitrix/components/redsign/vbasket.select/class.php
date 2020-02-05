<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;

use \Redsign\VBasket\Core;

class RedsignVBasketSelect extends CBitrixComponent
{
	protected $userBasketService;
	
	protected $active;
	protected $basketCollection;
	protected $counts;

	public function __construct($component = null)
	{
		parent::__construct($component);
		
		try {			
			$this->checkModules();

			$this->userBasketService = Core::container()->get('user_basket_service');

			$codeProvider = Core::container()->get('code_provider');
			$this->active = $codeProvider->get();
		}
		catch(\Exception $e){}
	}
	
	public function onPrepareComponentParams($params)
	{
		$params['USE_COUNTS'] = !isset($params['USE_COUNTS']) || !in_array($params['USE_COUNTS'], ['Y', 'N']);
		
		return $params;
	}

	protected function checkModules()
	{
		if (!Loader::includeModule('redsign.vbasket'))
		{
			throw new SystemException('redsign.vbasket module not installed');
		}
	}
	
	protected function loadBasketCollection()
	{
		$this->basketCollection = $this->userBasketService->getAll();
		
		if ($this->arParams['USE_COUNTS'])
		{
			$this->counts = $this->userBasketService->getCounts();
		}
	}

	protected function getResult()
	{
		$this->arResult = [];

		foreach ($this->basketCollection as $basketObject)
		{
			$data = [
				'ID' => $basketObject->getId(),
				'CODE' => $basketObject->getCode(),
				'~NAME' => $basketObject->getName(),
				'NAME' => htmlspecialcharsbx($basketObject->getName()),
				'~COLOR' => $basketObject->getColor(),
				'COLOR' => htmlspecialcharsbx($basketObject->getColor()),
				'SELECTED' => $basketObject->getCode() == $this->active
			];
			
			if ($this->arParams['USE_COUNTS'])
			{
				$data['CNT'] = isset($this->counts[$basketObject->getId()]) ? (int) $this->counts[$basketObject->getId()] : 0;
			}
			
			$this->arResult[] = $data;
		}
	}

	public function executeComponent(): void
	{
		try
		{
			$this->checkModules();
			
			if (Core::isEnabled())
			{				
				$this->setFrameMode(false);
				$this->loadBasketCollection();
				
				$this->getResult();

				$this->includeComponentTemplate();
			}
		}
		catch(SystemException $e)
		{
			// \ShowError($e->getMessage());
		}
	}
}