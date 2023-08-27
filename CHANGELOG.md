# Changelog

All notable changes to `sync-enum-types` will be documented in this file.

## Feat: Can use other imported Enums - 2023-08-27

When declaring a case like this:

```case TEST_CASE = OtherEnum::SOME_CASE->value;
```
the values are now resolved correctly.

## Fixed Bug - 2023-08-07

Parsing Issue

## Includes Type Specifications for Cases Files - 2023-08-07

Synced Cases files are now typed correctly.

## Feat: Changed default typescript folder path - 2023-05-03

This should work better with most project structures
