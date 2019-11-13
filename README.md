# Case Study - Namal Dissanayake

This module developed by Namal Dissanayake for Ajalan's case study.

## Installation
- Create magento project
```sh
composer create-project --repository=https://repo.magento.com/ magento/project-community-edition
```
- Create a database, setup virtual hosts and setup RabbitMQ.
- Navigate to codepool directory inside the cloned repository.
- Install composer packages.
```sh
composer install
```
- Install Magento using below command
```sh
php bin/magento setup:install --base-url=http://magento2.local/ --base-url-secure=https://magento2.local/ \
--db-host=localhost --db-name=db_name --db-user=db_user --db-password=db_pw \
--admin-firstname=FirstName --admin-lastname=LastName --admin-email=john.doe@example.com \
--admin-user=admin_uname --admin-password=admin_pword --use-rewrites=1 --backend-frontname=admin \
--amqp-host="localhost" --amqp-port="5672" --amqp-user="rabbitmq_user" --amqp-password="rabbitmq_pword" --amqp-virtualhost="/"
```
- Run setup upgrade.
```sh
php bin/magento setup:upgrade
```
- If you are on production mode run di compile and static content deploy as well.
```sh
php bin/magento setup:di:compile; php bin/magento setup:static-content:deploy -j 4
```
- You can visit the store now and start testing.

## Case 2 - CustomCatalog module
As requested, custom module have been developed for install using composer package manager. Respository only containing the module have been submited to packagist.org. So you can use below command to install the package to your project.
```sh
composer require ajlan/module-custom-catalog
```
### Case 2.1 -Magento Admin Customisation

#### Case 2.1.1
It should add a "CustomCatalog" menu on PRODUCTS section 

![Imgur](https://i.imgur.com/Ajlbila.png)

#### Case 2.1.2
When we click on CustomCatalog link it should list all added products. And it should also have filtering options.

![Imgur](https://i.imgur.com/cCVDupTl.png?2)

#### Case 2.1.3
We should be able to add/edit products.

![Imgur](https://i.imgur.com/ullXSqjl.png)

In the screenshots we have many fields. Just need to cover these fields. ProductID,SKU,CopyWriteInfo,VPN for this case.

- ProductID (a unique identifier of a product, string) \ scope = global
- CopyWriteInfo (copy write information, text) \ scope = store
- VPN ( Vendor Product Number, string) \ scope = global
- SKU( string) \ scope = global

You just need to add above 4 fields to be listed, added and edited. Admin should be able to search with particular VPN and perform
CRUD operations.

### Case 2.2 - API Customisation
Below custom endpoints developed as per the requirement specified. On case 2.2.2, I have altered payload to match with magento default payload for a product save.

#### Case 2.2.1 - Get product(s) data by VPN

Endpoint: **rest/V1/product/getByVPN/:VPN**

Method: **GET**

#### Case 2.2.2 - MQ product creation

Endpoint: **rest/V1/product/update**

Method: **PUT**

Sample payload:

**Note:** Since I have utilized Magento default product entity. Lets pass the payload as Magento's product entity. Which we could directly assigned for a Product object with the API call. So the payload here is bit different than the provided example.

```json
{
  "product": {
    "sku": "simple-product-sku",
    "name": "Simple Product Name",
    "price": 99,
    "status": 1,
    "visibility": 4,
    "type_id": "simple",
    "attribute_set_id": 4,
    "custom_attributes": [
      {
        "attribute_code": "description",
        "value": "Simple product description"
      },
      {
        "attribute_code": "tax_class_id",
        "value": "2"
      },
      {
        "attribute_code": "ProductID",
        "value": "PUID_1"
      },
      {
        "attribute_code": "CopyWriteInfo",
        "value": "Copy Write Information"
      },
      {
        "attribute_code": "VPN",
        "value": "VPN1"
      }
    ]
  }
}
```