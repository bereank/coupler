## ⚗️ About Coupler
A dedicated Laravel-PHP library crafted exclusively for seamless integration with the SAP Business One Service Layer API.

It's heavily inspired by syedhussim's [php-sapb1](https://github.com/syedhussim/php-sapb1).
## Installation

You can install the package via composer:

```bash
composer require bereank/coupler
```
## Usage
Create an array to store your SAP Business One Service Layer configuration details. 

```php
$config = [
    'https' => true,
    'host' => 'IP or Hostname',
    'port' => 50000,
    'sslOptions' => [
        "cafile" => "path/to/certificate.crt",
        "verify_peer" => true,
        "verify_peer_name" => true,
    ],
    'version' => 2
];
```

Create a new Service Layer session.

```php
$sap = SAPClient::createSession($config, 'SAP UserName', 'SAP Password', 'Company');
```

The static `createSession()` method will return a new instance of `SAPClient`. The SAPClient object provides a `service($name)` method which returns a new instance of Service with the specified name. Using this Service object you can perform CRUD actions.

### Querying A Service

The `queryBuilder()` method of the Service class returns a new instance of Query. The Query class allows you to use chainable methods to filter the requested service.

The following code sample shows how to filter Sales Orders using the Orders service.

```php
$sap = SAPClient::createSession($config, 'SAP UserName', 'SAP Password', 'Company');
$orders = $sap->getService('Orders');

$result = $orders->queryBuilder()
    ->select('DocEntry,DocNum')
    ->orderBy('DocNum', 'asc')
    ->limit(5)
    ->findAll(); 
```
The `findAll()` method will return a collection of records that match the search criteria. To return a specific record using an `id` use the `find($id)` method.

```php
...
$orders = $sap->getService('Orders');

$result = $orders->queryBuilder()
    ->select('DocEntry,DocNum')
    ->find(123456); // DocEntry value
```
Depending on the service, `$id` may be a numeric value or a string. If you want to know which field is used as the id for a service, call the `getMetaData()` method on the Service object as shown below.

```php
...
$meta = $orders->getMetaData();
```

### Creating A Service

The following code sample shows how to create a new Sales Order using the create() method of the Service object.

```php
...
$orders = $sap->getService('Orders');

$result = $orders->create([
    'CardCode' => 'BP Card Code',
    'DocDueDate' => 'Doc due date',
    'DocumentLines' => [
        [
            "ItemCode" => "Item Code",
            "Quantity" => 100,
        ]
    ]
]);
```
You must provide any User Defined Fields that are required to create a Sales Order. If successful, the newly created Sales Order will be returned as an object.

### Updating A Service

The following code sample demonstrates how to update a service using the `update()` method of the Service object.

```php
...
$orders = $sap->getService('Orders');

$result = $orders->update(19925, [
    'Comments' => 'Comment added here'
]);
```
Note that the first argument to the update() method is the `id` of the entity to update. In the case of a Sales Order the `id` is the DocEntry field. If the update is successful a boolean true value is returned.

### Adding Headers

You can specify oData headers by calling the headers() method on a Service instance with an array of headers.

```php
...
$orders = $sap->getService('Orders');
$orders->headers(['Prefer' => 'odata.maxpagesize=0']);

$result = $orders->queryBuilder()
    ->select('DocEntry,DocNum')
    ->find(123456); // DocEntry value
```
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Berean Kibet](https://github.com/bereank)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
