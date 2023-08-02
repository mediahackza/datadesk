# DataDesk

DataDesk is a data management system for (mostly) journalists and developed by Media Hack Collective with funding from the Google News Initiative.

## Objectives

DataDesk's primary objective is to make it easier for journalists (and other users) to collect, manage, manipulate and share data. DataDesk is intentionally designed around users that have limited experience in using data. It should be as easy as possible for users to upload and share data using DataDesk.

Data in DataDesk is primarily managed in Google Sheets files or CSV files. DataDesk tracks these files and makes them available in CSV and JSON formats.

JSON formats can be accessed remotely via a basic API and can be used to integrate into data tools and visualisations. See examples of tools built on DataDesk below.

In its most basic form DataDesk is a one-click tool to create a JSON API feed out of a CSV or Google Sheet, although it is possible to do much more than just that.

### Principles

DataDesk is built around a number of core ideas:

##### Easily accessible to all users

Data is primarily stored in Google Sheets or comma separated value files (CSVs) which means that users only need to be able to access and use a Google Sheet spreadsheet to contribute.

##### Incremental updates

While DataDesk is able to handle large datasets it also enables the decelopment of small data sets that are updated in small updates. This is the most common use of DataDesk.

##### Managing access to dataseta

Datasets are either active (publicly available) or inactive (not genreally available). Deleted datasets are available to adminstrators but not publicly available.

##### Simplifying the complex

Working with data very often requires complex transformations of the data, often outside of the skillset of many journalists. For example, much of the data released by country statistics bodies is released as "wide" data. DataDesk has in-built functions for converting data like this to "long" data (see below for a description of this).
DataDesk is built on many of the principles and techniques recommended by [tidy data](https://cran.r-project.org/web/packages/tidyr/vignettes/tidy-data.html) which makes it simpler for end users to achieve valuable insights.

---

## Functionality

DataDesk is a work in progress but already includes a range of functions useful to anyone working with data. These include:

#### Adding data

Data can be added to DataDesk from either a CSV or Google Sheet link.
The CSV file is uploaded and stored on the server while the Google Sheet is stored as a reference, so that any changes to the sheet will be reflected in the data.

The advantage of storing the data in a Google Sheet is that is makes it very easy for anyone to manage or supplement the data, without requiring technical or programming knowledge. At the same time the data is available as a JSON feed which can be used to power visualisations and stories. This means that the data can be "live" in the sense that the most recent changes to the data are available to the front-end applications. For an example of this, the [Loadshedding Tracker](https://loadshed.theoutlier.co.za) run by The Outlier is updated as soon as new changes to schedules are announced, and the front-end of the tracker is updated hourly with the latest information.

#### Managing data

Basic managing functions include **date stamp** of changes, **tagging**, **descriptions**, **active(public)** and **inactive(private)** data sets, searches.

- **CSV/Google Sheets to JSON API** for tables
- **Pivot Longer** function built-in. Converts "wide" data to "long" data. (See [Statology](https://www.statology.org/long-vs-wide-data) for example)
- **Merge tables** (beta) combines multiple tables (sheets or CSVs) into new data sets.
- **Query Builder** (beta) makes it possible to build custom data sets from stored tables. The builder does not require SQL knowledge to use.

#### Separate back-end and front-end

DataDesk is primarily a PHP and MySQL application. The management interface is primarily PHP and manages the basic input, editing functions.
DataDesk also exposes a Rest API so that various front-end versions can be created. For example: [Data @ The Outlier](https://data.theoutlier.co.za) is a DataDesk powered front-end.
DataDesk was built this way so that variations on the interface could be achieved without limiting the back-end functionality.

---

## Examples

Media Hack Collective and The Outlier use DataDesk as the primary data repository for all of its tools. These include:

- [Loadshedding Tracker](https://loadshed.theoutlier.co.za)
- [Mayors Ages](https://tools.theoutlier.co.za/mayor-ages)
- [Unemployment Tracker](https://www.theoutlier.co.za/unemployment)
- [SA Municipal Audits Tracker](https://tools.theoutlier.co.za/municipal-audits)

The Outlier maintains a repository of data based on DataDesk here:

- [Data@TheOutlier](https://data.theoutlier.co.za/)
