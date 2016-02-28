FrchoCrontaskBundle
===================

Creating new cron jobs for every trivial tasks is a time-consuming task, though.
And depending on the environment your application will be hosted in, you may not
always be able to add a cron job to the system whenever you feel like it.



Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require frcho/crontask "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

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

            new Frcho\Bundle\CrontaskBundle\FrchoCrontaskBundle(),

        );

        // ...
    }

    // ...
}
```

Implementing interval-based cron tasks in Symfony2 using Symfony commands and a Doctrine entity
========

The Doctrine entity known as the 'CronTask'

If we review the requirements real quick, we'll notice that we want our tasks to
be able to do the following:

Run at a specified interval
Perform certain actions

This sounds as simple as it is. To begin with, we'll create a CronTask entity
we can persist to our database. This entity should be contain an array
of actions it can execute. We'll be using Symfony commands as our actions. It'd
also be handy if each task had its own identifier.

Usage
=====

The master command

```bash
$ php app/console crontasks:run
```

After visiting /crontasks/test, you should now have a single CronTask in your
database, ready to be executed. Now, you could execute
php app/console crontasks:run yourself, or add that command as an actual cron
job that is executed once every few minutes like so:

```bash
$ crontab -e
Now add your cron job:

# Run every five minutes
*/5 * * * * php /var/www/your-project/app/console crontasks:run

And there you have it. One task to rule them all.
```


Source
=====
https://inuits.eu/blog/creating-automated-interval-based-cron-tasks-symfony2

