# Magento 2 AutoCancelOrder Module

A Magento 2 module that cancel old pending and processing orders


Installation
-----------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).
    
1. Go to Magento2 root folder

2. Enter following commands to install module:

    ```bash
    composer require bitixel/magento2-auto-order-cancel
    ```

3. Enter following commands to enable module:

    ```bash
    php bin/magento module:enable Bitixel_AutoCancelOrder --clear-static-content
    php bin/magento setup:upgrade
    ```    
    
    
