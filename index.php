<?php
	require_once '../../views/_secureHead.php';
	require_once '../../models/_add.php';
	require_once '../../models/_table.php';

	KeyManager::initDB(new KeyManager());
	
	if( isset ($sessionManager) && $sessionManager->isAuthorized () ) {
		$KEYSTORE_ID = request_isset ('id');
		$name = request_isset ('name');
		$private_key = request_isset ('private_key');
		$public_key = request_isset ('public_key');
		$passphrase = request_isset ('passphrase');
		
		switch ($page_action) {
			case ('update_by_id') :
				$db_update_success = KeyManager::updateRecord ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key, $passphrase);
				break;
			case ('add_key') :
				$db_add_success = KeyManager::addRecord ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key, $passphrase);
				break;
			case ('delete_by_id') :
				$db_delete_success = KeyManager::deleteRecord ($KEYSTORE_ID, $USER_ID);
				break;
		}

		$keyman_records = KeyManager::getAllRecords( $USER_ID );

		$alt_menu = getAddButton();

		// build add view
		$addView = new AddView ('Add', 'add_key');
		$addView->addRow ('name', 'Name');
		$addView->addRow ('private_key', 'Private key');
		$addView->addRow ('public_key', 'Public key');
		$addView->addRow ('passphrase', 'Passphrase');

		// build table view
		$tableView = new TableView ( array ('Name', 'Public key', 'Private key', 'Passphrase', '') );

		foreach ($keyman_records as $record) {
			$tableView->addRow ( array ( TableView::createCell ('name', $record->getName() ),
										 TableView::createCell ('public_key', $record->getPublicKey() ) ,
										 TableView::createCell ('private_key', $record->getPrivateKey() ),
										 TableView::createCell ('passphrase', '<span class="mask">************</span><span class="password-actual">' . $record->getPassphrase() . '</span>' ),
										 TableView::createEdit ($record->getKeystoreId() )
									   )
							   );
		}

		// load views to be used in front end
		$views_to_load = array();
		$views_to_load[] = '../../views/_add.php';
		$views_to_load[] = '../../views/_table.php';
		
		include '../../views/_generic.php';
	}
