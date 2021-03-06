Crontask Bundle
===================

Creating new cron jobs for every trivial tasks is a time-consuming task, though.
And depending on the environment your application will be hosted in, you may not
always be able to add a cron job to the system whenever you feel like it.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console and execute the following command to download the latest stable version of this bundle:

```bash
$ composer require frcho/crontask 1.1.0
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.


Implementing interval-based cron tasks in Symfony5 using Symfony commands and a Doctrine entity
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
<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronTasksDefaultCommand extends Command
{

    protected function configure()
    {

        $this->setName('crontasks:default')->setDescription('Creates the commands by default in database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $container = $this->getApplication()->getKernel()->getContainer();
        $defaultCommands = array(
            array(
                "name" => "Example asset symlinking task",
                "interval" => 2, // Run once every 2 minutes,
                "range" => 'minutes',
                "commands" => 'assets:install --symlink web',
                "isHide" => false, /* "isHide == true, if you have enable view for this bundle, This command doesn't show in the view schedule task" */
                "enabled" => true
            ),
            array(
                "name" => "Example asset task",
                "interval" => 1, // Run once every hour
                "range" => 'hours',
                "commands" => 'cache:clear',
                "enabled" => false
            ),
        );

        $container->get('frcho.crontask_default')->setArrayCommands($defaultCommands);
        return 0;
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

The command to run other commands

```bash
$ php bin/console crontasks:run
```

The command to populate de database, with the commands to run by crontask:run

```bash
$ php bin/console crontasks:default
```

After execute crontasks:default, you should now have a single CronTask in your
database, ready to be executed.

Now, you could execute **php bin/console crontasks:run** yourself.
Or add that command as an actual cron job that is executed once every few minutes like so:

```bash
$ crontab -e
Now add your cron job:

# Run every five minutes
*/5 * * * * php path-your-project/bin/console crontasks:run

And there you have it.
One task to rule them all.
```
<!--
In order to see the view, the bundle comes with a implementation.

Import the routing to your `routing.yml`
```yaml
frcho_cron_task:
    resource: "@FrchoCrontaskBundle/Resources/config/routing.yml"

``` -->

Update the database schema :
```bash
bin/console doctrine:schema:update --force
```

License
-------

This bundle is under the [MIT license](https://github.com/frcho/crontask/blob/master/Resources/meta/LICENSE)
