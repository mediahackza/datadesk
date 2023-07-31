# DataDesk

DataDesk is a data management system for (mostly) journalists and developed by Media Hack Collective with funding from the Google News Initiative.

## Objectives

DataDesk's primary objective is to make it easier for journalists (and other users) to collect, manage, manipulate and share data. DataDesk is intentionally designed around users that have limited experience in using data. It should be as easy as possible for users to upload and share data using DataDesk.

Data in DataDesk is primarily managed in Google Sheets files or CSV files. DataDesk tracks these files and makes them available in CSV and JSON formats.

JSON formats can be accessed remotely via a basic API and can be used to integrate into data tools and visulisations. See examples of tools built on DataDesk below.

In its most basic form DataDesk is a one-click tool to create a JSON API feed out of a CSV or Google Sheet, although it has

---

## Functionality

DataDesk is a work in progress but already includes a range of functions useful to anyone working with data. These include:

#### Managing data

Basic managing functions include **date stamp** of changes, **tagging**, **descriptions**, **active(public)** and **inactive(private)** data sets, searches

- **CSV/Google Sheets to JSON API** for tables
- **Pivot Longer** function built-in. Converts "wide" data to "long" data. (See [Statology](https://www.statology.org/long-vs-wide-data) for example)
- **Merge tables** (beta) combines multiple tables (sheets or CSVs) into new data sets.
- **Query Builder** (beta) makes it possible to build custom data sets from stored tables. The builder does not require DQL knowledge to use.

---

---

## Examples

Media Hack Collective and The Outlier use DataDesk as the primary data repository for all of its tools. These include:

- [Loadshedding Tracker](https://loadshed.theoutlier.co.za)
- [Mayors Ages](https://tools.theoutlier.co.za/mayor-ages)
- [Unemployment Tracker](https://www.theoutlier.co.za/unemployment)
- [SA Municipal Audits Tracker](https://tools.theoutlier.co.za/municipal-audits)

The Outlier maintains a repository of data based on DataDesk here:

- [Data@TheOutlier](https://data.theoutlier.co.za/)
