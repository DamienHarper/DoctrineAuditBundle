# DoctrineAuditBundle 

[![GitHub license](https://img.shields.io/github/license/DamienHarper/DoctrineAuditBundle.svg)](https://github.com/DamienHarper/DoctrineAuditBundle/blob/master/LICENSE)
[![release-version-badge]][packagist]
![php-version-badge]

This bundle creates audit logs for all doctrine ORM database related changes:

- inserts and updates including their diffs and relation field diffs.
- many to many relation changes, association and dissociation actions.
- if there is an user in token storage, it is used to identify the user who made the changes.
- the audit entries are inserted within the same transaction during **flush**, if something fails the state remains clean.

Basically you can track any change from these log entries if they were
managed through standard **ORM** operations.

**NOTE:** audit cannot track DQL or direct SQL updates or delete statement executions.

This bundle is inspired by [data-dog/audit-bundle](https://github.com/DATA-DOG/DataDogAuditBundle.git) and 
[simplethings/entity-audit-bundle](https://github.com/simplethings/EntityAuditBundle.git)


Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```bash
composer require damienharper/doctrine-audit-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
composer require damienharper/doctrine-audit-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new DH\DoctrineAuditBundle\DHDoctrineAuditBundle(),
        );

        // ...
    }

    // ...
}
```


Configuration
=============

### Audited entities and properties

By default, DoctrineAuditBundle won't audit any entity, you have to configure which entities have to be audited.

```yaml
// app/config/config.yml (symfony < 3.4)
// config/dh_doctrine_audit.yaml (symfony >= 3.4)
dh_doctrine_audit:
    entities:
        MyBundle\Entity\MyAuditedEntity1: ~
        MyBundle\Entity\MyAuditedEntity2: ~
```

All `MyAuditedEntity1` and `MyAuditedEntity2` properties will be audited. 
Though it is possible to exclude some of them from the audit process.

```yaml
// app/config/config.yml (symfony < 3.4)
// config/dh_doctrine_audit.yaml (symfony >= 3.4)
dh_doctrine_audit:
    entities:
        MyBundle\Entity\MyAuditedEntity1: ~   # all MyAuditedEntity1 properties are audited
        MyBundle\Entity\MyAuditedEntity2:
            ignored_columns:                  # properties ignored by the audit process
                - createdAt
                - updatedAt
```

It is also possible to specify properties that are globally ignored by the audit process.

```yaml
// app/config/config.yml (symfony < 3.4)
// config/dh_doctrine_audit.yaml (symfony >= 3.4)
dh_doctrine_audit:
    ignored_columns:    # properties ignored by the audit process in any audited entity
        - createdAt
        - updatedAt
```

### Audit tables naming format

Audit table names are composed of a prefix, the audited table name and a suffix. 
By default, the prefix is empty and the suffix is `_audit`. Though, they can be customized.

```yaml
// app/config/config.yml (symfony < 3.4)
// config/dh_doctrine_audit.yaml (symfony >= 3.4)
dh_doctrine_audit:
    table_prefix: ''
    table_suffix: '_audit'
```

### Displaying firewall information

The name of the firewall and the fully qualified class name of the User object is recorded, but is not displayed in the template by default. This can be useful in projects that use more than one firewall. Set `show_user_firewall` to `true` to enable the display of this data.

```yaml
// app/config/config.yml (symfony < 3.4)
// config/dh_doctrine_audit.yaml (symfony >= 3.4)
dh_doctrine_audit:
    show_user_firewall: false
```

### Creating audit tables

Open a command console, enter your project directory and execute the
following command to review the new audit tables in the update schema queue.

```bash
# symfony < 3.4
app/console doctrine:schema:update --dump-sql 
```

```bash
# symfony >= 3.4
bin/console doctrine:schema:update --dump-sql 
```

**Notice**: DoctrineAuditBundle currently **only** works with a DBAL Connection and EntityManager named **"default"**.


#### Using [Doctrine Migrations Bundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html)

```bash
# symfony < 3.4
app/console doctrine:migrations:diff
app/console doctrine:migrations:migrate
```

```bash
# symfony >= 3.4
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```

#### Using Doctrine Schema
    
```bash
# symfony < 3.4
app/console doctrine:schema:update --force
```

```bash
# symfony >= 3.4
bin/console doctrine:schema:update --force
```

### Audit viewer

Add the following routes to the routing configuration to enable the included audits viewer.

```yaml
// app/config/routing.yml (symfony < 3.4)
// config/routes.yaml (symfony >= 3.4)
dh_doctrine_audit:
    resource: "@DHDoctrineAuditBundle/Controller/"
    type: annotation
``` 


Usage
=====

**audit** entities will be mapped automatically if you run schema update or similar.
And all the database changes will be reflected in the audit logs afterwards.


Audits cleanup
==============

DoctrineAuditBundle provides a convenient command that helps you cleaning audit tables.
Open a command console, enter your project directory and execute:

```bash
# symfony < 3.4
app/console audit:clean
```

```bash
# symfony >= 3.4
bin/console audit:clean
```

By default it cleans audit entries older than 12 months. You can override this by providing the number of months 
you want to keep in the audit tables. For example, to keep 18 months:

```bash
# symfony < 3.4
app/console audit:clean 18
```

```bash
# symfony >= 3.4
bin/console audit:clean 18
```

It is also possible to bypass the confirmation and make the command un-interactive if you plan to schedule it (ie. cron)

```bash
# symfony < 3.4
app/console audit:clean --no-confirm
```

```bash
# symfony >= 3.4
bin/console audit:clean --no-confirm
```


License
=======

DoctrineAuditBundle is free to use and is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php)

<!-- Badges -->
[packagist]: https://packagist.org/packages/damienharper/doctrine-audit-bundle
[release-version-badge]: https://img.shields.io/packagist/v/damienharper/doctrine-audit-bundle.svg?style=flat&label=release
[license]: LICENSE
[license-badge]: https://img.shields.io/github/license/DamienHarper/DoctrineAuditBundle.svg?style=flat
[php-version-badge]: https://img.shields.io/packagist/php-v/damienharper/doctrine-audit-bundle.svg?style=flat
