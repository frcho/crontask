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
            //new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            //new Fkr\CssURLRewriteBundle\FkrCssURLRewriteBundle(),

        );

        // ...
    }

    // ...
}
```


In order to see the view, the bundle comes with a implementation.

import the routing to your `routing.yml`
```yaml
frcho_cron_task:
    resource: "@FrchoCrontaskBundle/Resources/config/routing.yml"

```

Update the database schema :
```bash
symfony 3.0
bin/console doctrine:schema:update --force

symfony 2.8
app/console doctrine:schema:update --force
```

You must add FrchoCrontaskBundle to the assetic.bundle config
```bash
assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    bundles:        [FrchoCrontaskBundle]
    #java: /usr/bin/java
    filters:
        cssrewrite: ~
        #closure:
     
fkr_css_url_rewrite:
    rewrite_only_if_file_exists: true
    clear_urls: true
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

Create your command
====


```bash
namespace Your\ExampleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronTasksDefaultCommand extends ContainerAwareCommand {

    protected function configure() {

        $this->setName('crontasks:default')->setDescription('Creates the commands by default in database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $container = $this->getContainer();
        $defaultCommands = array(
            array("name" => "Example asset symlinking task",
                "interval" => 120 /* Run once every 2 minutes */,
                "range" => 'minutes',
                "commands" => 'assets:install --symlink web',
                "enabled" => true
            ),
            array("name" => "Example asset  task",
                "interval" => 3600 /* Run once every hour */,
                "range" => 'hours',
                "commands" => 'cache:clear',
                "enabled" => false
            ),
        );

        $container->get('frcho.crontask_default')->setArrayCommands($defaultCommands);
    }

}
```
##Note: 
range support:
* minutes
* hours
* days


Usage
=====

The master command

```bash
$ php app/console crontasks:run
```
```bash
$ php bin/console crontasks:default
```

After execute crontasks:default, you should now have a single CronTask in your
database, ready to be executed. Now, you could execute
php bin/console crontasks:run yourself, or add that command as an actual cron
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

