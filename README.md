# EasyAdWords
EasyAdWords is an easy-to-use and customizable library that simplifies the basic usage cases of the AdWords API with PHP Client library. It basically allows **simpler reporting process** with *downloading* and *parsing* the report, and also allows entity operations such as **getting**, **creating** and **removing** campaigns, ad groups and keywords.
  
For example, an `Account Performance Report` can easily be downloaded and formatted like the following simple snippet:
```php
    $config = new ReportConfig([
        "adwordsConfigPath" => 'my_adwords_config_file_path',
        "clientCustomerId"  => 'my_client_customer_id',
        "refreshToken"      => 'my_oauth_refresh_token',
        "fields"            => ['Date', 'Clicks', 'Impressions'],
        "startDate"         => '2017-01-22',
        "endDate"           => '2017-01-25'
    ]);

    // The report object is initialized.
    $report = new AccountPerformanceReport($config);     
  
    // Download the report and format it to a flat array.   
    $report->download()->format();    
    
    // Access the report property of the object.    
    $report->getReport();
```

## Table of Contents

- [Installation](#installation)
- [Config](#config)
- [Reporting](#reporting)
- [Entities](#entities)
  * [Campaign](#campaign)
  * [AdGroup](#adgroup)
  * [Keyword](#keyword)
    + [`Keyword`](#keyword)
    + [`KeywordBatch`](#keywordbatch)
- [Naming](#naming)

    
----------------
  
## Installation
`composer require adprolabs/easy-adwords`

----------------

## Config
Basically, EasyAdWords aims to create an easy-to-use interface between the developer and the *AdWords PHP Client library*. In order to keep this process simple, EasyAdWords uses configuration objects across different entities and operations. All of these config objects are specialized for the service they are used with; however, they all extend the base `Config` class. The `Config` class contains the following fields, as they are required for every service used with EasyAdWords:
  
- `adwordsConfigPath` => The path to the AdWords config file. If this is not given, the package looks for `adsapi_php.ini` file in the project root.
- `clientCustomerId`  => ID of the customer to operate on. **Required.** 
- `refreshToken`      => Refresh token of the authenticated user. **Required.**
- `fields`            => Fields to get. **Required for get operations.**     
  
Keep in mind that these fields are **required** for all of the config objects, except that the `adwordsConfigPath` key, which seems to be not necessary, however if not given, the AdWords PHP Client library will look for the `adsapi_php.ini` file in the project root, therefore it is **required to have this file**. 
   
## Reporting
EasyAdWords contains some basic classes that allows to get reports from AdWords easily. The following are available reporting classes for now:
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
EasyAdWords offers basic entity operations for campaigns, ad groups and keywords. Basically, all of these three entities are able to perform 3 basic operations:
- `create`  => Creates an instance of the entity in Google AdWords.
- `get`     => Gets the instances of the entity that fits specified criteria.
- `remove`  => Removes the instance of the entity from Google AdWords.
 
Basically, all of these entity objects operate with special config objects, just like the reporting objects. Therefore, an appropriate config object for the entity must be created, and then, the entity object can be created by using this config object.
 
All the entity objects are extending the base class `Entity` and implementing the `EntityInterface`. Therefore, after every operation, result of the operation is accessible as a property of the entity object, called `operationResult`, which can be accessed by using the method `getOperationResult()`.
  
Available entities are **Campaign**, **Ad Group** and **Keyword**  for now. All the entities have their corresponding config objects, such as `CampaignConfig`.
  
The usage is same across the entity methods `create`, `get` and `remove`. As the nature of the `get` operation, the results can be filtered by specifying the `predicates` parameter. If there are no predicates set, all the instances of the entity will be listed.
   
### Campaign
Like all the entities, the *Campaign* entity operates on a config object of the class `CampaignConfig`. The `CampaignConfig` class contains the following fields, in addition to the fields in the base `Config` class:

```
'campaignId'                     => ID of the campaign.
'campaignName'                   => Name of the campaign.
'advertisingChannelType'         => Advertising channel of the campaign. Default is 'SEARCH'.
'status'                         => Status of the campaign. Default is 'PAUSED'.
'budget'                         => Budget of the campaign, e.g. 50 means 50$. Default is 50.
'budgetName'                     => Name of the budget.
'biddingStrategyType'            => Bidding strategy type of the campaign. Default is 'MANUAL_CPC'.
'budgetDeliveryMethod'           => Budget delivery method of the campaign. Default is 'STANDARD'.
'targetGoogleSearch'             => Target Google search if true. Default is true.
'targetSearchNetwork'            => Target search network if true. Default is true.
'targetContentNetwork'           => Target content network if true. Default is true.
'startDate'                      => Start date of the campaign. Default is today.
'endDate'                        => End date of the campaign.
'adServingOptimizationStatus'    => Ad serving optimization status of the campaign.
'servingStatus'                  => Serving status of the campaign. Default is 'SERVING'.
```
  
Not all these parameters are required for all of the campaign operations, some are needed for `create` operation, while some others are required fore `remove` operation. However, if you do not provide some important properties, especially when creating a campaign, the defaults will be used, which may or may not be a problem for you. *Keep in mind that, when creating a campaign, EasyAdWords sets the campaign status to 'PAUSED' in order to not to spend money on a misconfigured campaign, if otherwise is not stated.* 
  
A basic usage of the campaign entity is as follows:
```php
    // Create the campaign configuration object.
    $config = new CampaignConfig([
        "adwordsConfigPath" => 'my_adwords_config_file_path',
        "clientCustomerId"  => 'my_client_customer_id',
        "refreshToken"      => 'my_oauth_refresh_token',
        "startDate"         => '2017-06-22',
        "campaignName"      => "EasyAdWords_TestCampaign_".uniqid(),
        "budget"            => 100
    ]);
   
    // Create the campaign object with the config object.
    $campaign = new Campaign($config);
    
    // Create the campaign on Google AdWords.
    $campaign->create();
  
    // Get the result of the campaign creation.
    $campaign->getOperationResult();

```

### AdGroup
`AdGroup` entity is using the same simple set of methods and configuration logic. It works by using the `AdGroupConfig` object. The `AdGroupConfig` object consists of the following fields:

```
'campaignId'     =>  The campaign ID of the ad group.
'adGroupName'    =>  The name of the ad group.
'adGroupId'      =>  The ID of the ad group to operate on.
'status'         =>  The status of the ad group, must be an AdGroupStatus instance. Default is 'PAUSED'.
'bid'            =>  The bid amount to give the ad group, without extra zeros, e.g. 50 means 50$.
```

Some of these fields are required for some of the operations. For instance, `campaignId`, `adGroupName` and `bid` are required fields for creating an ad group.
 
A basic usage of the `AdGroup` entity is as follows:
```php
    // Create the ad group configuration object.
    $config = new AdGroupConfig([
        "adwordsConfigPath" => 'my_adwords_config_file_path',
        "clientCustomerId"  => 'my_client_customer_id',
        "refreshToken"      => 'my_oauth_refresh_token',
        "campaignId"        => 'my_campaign_id',
        "adGroupName"       => 'EasyAdWords_AdGroup_TEST',
        "bid"               => 25
    ]);
   
    // Create the ad group object with the config object.
    $adGroup = new AdGroup($config);
    
    // Create the ad group on Google AdWords.
    $adGroup->create();
  
    // Get the result of the ad group creation.
    $adGroup->getOperationResult();

```
   
### Keyword
Keyword entity is a candidate for both singular and batch operations. Therefore, there are 2 different classes for Keyword entities. One of them is `Keyword` entity, which is used just like the `Campaign` and `AdGroup` entities; same set of methods and the same initialization process with `KeywordConfig` object. However, this class is intended to be used for singular entity operations, and in the case of keywords, this is not very useful. There is a need for batch keyword operations and AdWords Client library allows multiple operations to be sent over the same single request. Therefore, the second class `KeywordBatch` is implemented for batch keyword addition operations.
 
Both of these classes extend the same `KeywordBase` class.

#### `Keyword`
The `Keyword` class can be used with the same set of methods with other entities. It is initialized with the `KeywordConfig` object. The `KeywordConfig` object contains the following fields:
```
'keyword'       => Name of the keyword.
'keywordId'     => ID of the keyword to operate on.
'matchType'     => Match type of the keyword.
'finalUrls'     => Array of the final URLs for the keyword.
'adGroupId'     => ID of the ad group of the keyword.
'bid'           => Bid of the keyword.
'status'        => Status of the keyword.
```

The usage of the class is almost the same with the other entities:
```php
    // Create the keyword configuration object.
    $config = new KeywordConfig([
        "adwordsConfigPath" => 'my_adwords_config_file_path',
        "clientCustomerId"  => 'my_client_customer_id',
        "refreshToken"      => 'my_oauth_refresh_token',
        "adGroupId"         => 'my_ad_group_id',
        "keyword"           => 'space invaders',
        "matchType"         => KeywordMatchType::EXACT,
        "finalUrls"         => 'http://example.com',
        "bid"               => 25,
        "status"            => UserStatus::ENABLED
    ]);
   
    // Create the keyword object with the config object.
    $adGroup = new Keyword($config);
    
    // Create the keyword on Google AdWords.
    $adGroup->create();
  
    // Get the result of the keyword creation.
    $adGroup->getOperationResult();

```
  
#### `KeywordBatch`
`KeywordBatch` class allows to create multiple keywords in the Google AdWords at once. The class is constructed over a simple `KeywordBatchConfig` object, which has only one additional value other than the required base values, `batchSize`. `batchSize` value determines how many of the keywords will be grouped into one request. The batch size can be at most 5000, however  [the AdWords API documentation recommends passing at most 2000 operation per request](https://developers.google.com/adwords/api/docs/appendix/limits#general), therefore the default batch size is 2000 in `KeywordBatchConfig` objects.

The `KeywordBatch` object is simply constructed over the config object. After creating the instance, the object is ready to get the list of keywords. For each keyword to create, you need to create a `KeywordConfig` object in order to define the properties of the keyword and append this config object to the `KeywordBatch` object. This way, **you can append as many keywords as you want** to the batch object, and it will process all of those objects by batches of size `batchSize`. *Note that the `KeywordConfig` objects to append a keyword do not need to contain the `adWordsConfigPath`, `clientCustomerId` and `refreshToken` parameters, as those were given in the `KeywordBatchConfig` object.* In order to complete the operation and create keywords on Google AdWords, you need to call the method `mutate()` on the `KeywordBatch` object. Until you call the `mutate()` method, all the keywords will be just an array inside the object.

An example usage of `KeywordBatch` is as follows:
```php
    
    // Keywords to add.
    $keywords = ['quick brown fox','jumps over','the lazy dog'];
    
    // Create the keyword batch configuration object.
    $config = new KeywordBatchConfig([
            "adwordsConfigPath" => 'my_adwords_config_file_path',
            "clientCustomerId"  => 'my_client_customer_id',
            "refreshToken"      => 'my_oauth_refresh_token',
            "batchSize"         => 500
    ]);
  
    // Create the keyword batch object with the config object.
    $keywordBatch = new KeywordBatch($config);
  
    // Loop over the keywords and append them to the batch object. 
    foreach($keywords as $keyword) {
        $keywordConfig = new KeywordConfig([
            "adGroupId"         => 'my_ad_group_id',
            "keyword"           => $keyword,
            "matchType"         => KeywordMatchType::EXACT,
            "finalUrls"         => 'http://example.com',
            "bid"               => 25,
            "status"            => UserStatus::ENABLED
        ]);

        $keywordBatch.append($keywordConfig);
    }
    
    // Apply the new keywords to the Google AdWords.
    $keywordBatch->mutate();
```
  
With the batch config class, you can create keywords in batches, thus decrease the network requests for creating keywords. **The `KeywordBatch` class is only available for batch keyword addition operations for now.**

-------------
  
## Contribution
**Your participation to EasyAdWords development in any form is very welcome!** In order to contribute on the package, you need to clone the project and include it using the `repositories` option in your `composer.json` file. An example usage is as follows:

```json
    "repositories": [{
        "type": "vcs",
        "url": "path/to/easy-adwords"
    }]
```
  
Note that `repositories` is in the same level with `require` key, not nested inside something else. The `repositories` option works with the `vcs` value in `type`. Therefore, in order to test the package in another project with including it as a package like above, you need to commit your changes on the package and then `composer update` on the test project in order to get the latest changes you have made.
  
After successfully updating the project with self-explanatory commit messages and adding comments to the code in PHPDoc style with proper explanations, you can submit a pull request with an explanation of why the update was required and what was your solution for this problem.
  
Also, you can contribute to EasyAdWords by creating an issue with a well-defined and reproducable problem or giving feedback about the package and its design. Any form of participation is very welcomed.  
  
## License
EasyAdWords is released under the MIT Licence.