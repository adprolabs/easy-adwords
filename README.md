# EasyAdwords

EasyAdwords is an easy-to-use and customizable library that simplifies the basic usage cases of the AdWords API with PHP Client library. It basically allows **simpler reporting process** with *downloading* and *parsing* the report, and also allows entity operations such as **getting**, **creating** and **removing** campaigns, ad groups and keywords.
    
-------------

## Config
Basically, EasyAdwords aims to create an easy-to-use interface between the developer and the *AdWords PHP Client library*. In order to keep this process simple, EasyAdwords uses configuration objects across different entities and operations. All of these config objects are specialized for the service they are used with; however, they all extend the base `Config` class. The `Config` class contains the following fields, as they are required for every service used with EasyAdwords:
  
- `adwordsConfigPath` => The path to the AdWords config file. If this is not given, the package looks for `adsapi_php.ini` file in the project root.
- `clientCustomerId`  => ID of the customer to operate on. **Required.** 
- `refreshToken`      => Refresh token of the authenticated user. **Required.**    
Keep in mind that these fields are **required** for all of the config objects, except that the `adwordsConfigPath` key, which seems to be not necessary, however if not given, the AdWords PHP Client library will look for the `adsapi_php.ini` file in the project root, therefore it is **required to have this file**. 
   
## Reporting
EasyAdwords contains some basic classes that allows to get reports from AdWords easily. The following are available reporting classes for now:
- `AccountPerformanceReport`
- `AdGroupPerformanceReport`
- `AdPerformanceReport`
- `CampaignPerformanceReport`
- `CustomReport`
- `FinalUrlReport`
- `KeywordsPerformanceReport`
- `SearchQueryPerformanceReport`

All of these classes implement the same *interface*, therefore they all have the same methods to use. Basically, all of these report classes need to construct the report objects with a `ReportConfig` instance. An example `ReportConfig` object is as follows:

```php
    $config = new ReportConfig([
        "adwordsConfigPath" => 'my_adwords_config_file_path',
        "clientCustomerId"  => 'my_client_customer_id',
        "refreshToken"      => 'my_oauth_refresh_token',
        "fields"            => ['Date', 'Clicks', 'Impressions'],
        "startDate"         => '2017-01-22',
        "endDate"           => '2017-01-25',
        "predicates"        => [new Predicate('Clicks', PredicateOperator::GREATER_THAN, [10])]
    ]);
```

This `ReportConfig` object is constructed over a simple array. Available fields for the array are as follows:

- `fields`            => Fields to get with the report. **Required.** 
- `startDate`         => Start date of the report. **Required.** 
- `endDate`           => End date of the report. **Required.**
- `predicates`        => Predicates to filter the report.

The report object can now be initialized with the newly created `ReportConfig` object. After creating the report instance, we can simply call `download()` and `format()` methods on the object in order to download the report as CSV and format it to a simple, one-dimensional array respectively. An example usage is as follows:

```php
    // The report object is initialized.
    $report = new AccountPerformanceReport($config);     
  
    // Download the report and store it as a CSV string.   
    $report->download();    
    
    // Format the report CSV into a flat array.    
    $report->format();     
    
    // Access the report property of the object.    
    $report->getReport();    
```

This process is same for all of the reports. In addition to the available report classes, one can simply create a **custom report** by using the `CustomReport` class with the same config objects and methods. The only difference is, using a custom report, there needs to be a parameter given to the `download()` method of the report object, indicating the type of the report. The parameter given must be a constant from the `ReportDefinitionReportType` class of the AdWords PHP Client library. An example report using the same config object above is as follows:

```php
    // The custom report object is initialized.
    $report = new CustomReport($config);     
  
    // Download the URL Performance Report and store it as a CSV string.   
    $report->download(ReportDefinitionReportType::URL_PERFORMANCE_REPORT);    
    
    // Format the report CSV into a flat array.    
    $report->format();     
    
    // Access the report property of the object.    
    $report->getReport();    
```

Note that, after formatting the report, the CSV version of the report is not available.
    
----------

## Entities
EasyAdwords offers basic entity operations for campaigns, ad groups and keywords. Basically, all of these three entities are able to perform 3 basic operations:
- `create`  => Creates an instance of the entity in Google AdWords.
- `get`     => Gets the instances of the entity that fits specified criteria.
- `remove`  => Removes the instance of the entity from Google AdWords.
 
Basically, all of these entity objects operate with special config objects, just like the reporting objects. Therefore, an appropriate config object for the entity must be created, and then, the entity object can be created by using this config object.
 
All the entity objects are extending the base class `Entity` and implementing the `EntityInterface`. Therefore, after every operation, result of the operation is accessible as a property of the entity object, called `operationResult`, which can be accessed by using the method `getOperationResult()`.
  
Available entities are **Campaign**, **Ad Group** and **Keyword**  for now. All the entities have their corresponding config objects, such as `CampaignConfig`.
   
### Campaign
Like all the entities, the *Campaign* entity operates on a config object of the class `CampaignConfig`. The `CampaignConfig` class contains the following fields, in addition to the fields in the base `Config` class:

```
$campaignId                     => ID of the campaign.
$campaignName                   => Name of the campaign.
$advertisingChannelType         => Advertising channel of the campaign. Default is 'SEARCH'.
$status                         => Status of the campaign. Default is 'PAUSED'.
$budget                         => Budget of the campaign, e.g. 50 means 50$. Default is 50.
$budgetName                     => Name of the budget.
$biddingStrategyType            => Bidding strategy type of the campaign. Default is 'MANUAL_CPC'.
$budgetDeliveryMethod           => Budget delivery method of the campaign. Default is 'STANDARD'.
$targetGoogleSearch             => Target Google search if true. Default is true.
$targetSearchNetwork            => Target search network if true. Default is true.
$targetContentNetwork           => Target content network if true. Default is true.
$startDate                      => Start date of the campaign. Default is today.
$endDate                        => End date of the campaign.
$adServingOptimizationStatus    => Ad serving optimization status of the campaign.
$servingStatus                  => Serving status of the campaign. Default is 'SERVING'.
```
  
Not all these parameters are required for all of the campaign operations, some are needed for `create` operation, while some others are required fore `remove` operation. However, if you do not provide some important properties, especially when creating a campaign, the defaults will be used, which may or may not be a problem for you. *Keep in mind that, when creating a campaign, EasyAdwords sets the campaign status to 'PAUSED' in order to not to spend money on a misconfigured campaign, if otherwise is not stated.* 
  
A basic usage of the campaign entity is as follows:
```php
    // Create the campaign configuration object.
    $config = new CampaignConfig([
        "adwordsConfigPath" => 'my_adwords_config_file_path',
        "clientCustomerId"  => 'my_client_customer_id',
        "refreshToken"      => 'my_oauth_refresh_token',
        "fields"            => ['Date', 'Clicks', 'Impressions'],
        "startDate"         => '2017-06-22',
        "campaignName"      => "EasyAdwords_TestCampaign_".uniqid(),
        "budget"            => 50
    ]);
   
    // Create the campaign object with the config object.
    $campaign = new Campaign($config);
    
    // Create the campaign on Google AdWords.
    $campaign->create();
  
    // Get the result of the campaign creation.
    $campaign->getOperationResult();

```
  
The usage is same across the methods `create`, `get` and `remove`. As the nature of the `get` operation, the results can be filtered by specifying the `predicates` parameter. If there are no predicates set, all the campaigns will be listed.