# Dilbert3.0

### Those who use a Mac and run MAMP for localhost use the following

In `config/database.php` add the following line under `mysql` after `password`

```
'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
```
