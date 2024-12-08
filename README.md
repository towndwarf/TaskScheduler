# TaskScheduler
Interview @ BeachBum homeTask 

The task is to make a simple application that can take commands, 
such as 
- "Write to DB in 15 minutes"
and it will execute said command at exactly that time.

The commands can be received via CLI or via some frontend.

And the schedule can run via cron or via supervisor or some other utility.

Example:
- cli task:add +15m "Hello World"
- cli task:add +3m "Hello 23e3f2World"

Task will be assessed based on the overall architecture of the code, including but not limited to: folder structure, usage of PSR standards, design patterns, overall code cleanliness and general knowledge of PHP.

---

# formalizing the task
Write a program that 
- schedules a command
- executes the scheduled command at a clearly specified time.
- command is depicted as "Write to DB"

Since the task description is not clear,
- no 

I assume the following:
- task and time are guarded by double quotes, thus no AI preprocessing is required 
- timestamp is recognizable by ```strtotime```
- ```task to schedule``` is a bash executable, which might be run 'as is', no language or command extra recognition is required
- no repetative or subsequent tasks are expected? once again, no AI or any self-learning matrices for user import to be used

---

## Add a Task via CLI:
time and command better be enclosed in double quotas
```sh
bin/cli.php task:add "Run 'ping 127.0.0.1' at December 15, 2024"
bin/cli.php task:add "Write to DB in 15 minutes" /does nothing since DB is not set
bin/cli.php task:add +15m "Hello World"
bin/cli.php task:add "+ 1week 2 days 4 hours" "ls -w"
bin/cli.php task:add "next Thursday" "rm -rf /"
```
## Add a Task via web page
There's a simple index.php web page at a \public folder
Tasks might be added via this page. 
(less robust)

## Run Scheduled Tasks via CLI:
```sh
bin/cli.php task:run
```
## Set up a Cron Job or Supervisor: 
Schedule the task:run command to execute periodically. 
e.g. every second:
```sh
* * * * * /path/to/bin/cli.php task:run >/dev/null 2>&1
```
