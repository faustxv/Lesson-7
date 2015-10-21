Simple site for lesson #7.

Installation:

1. Create a MySQL database.

2. Add a table:

```sql
  CREATE TABLE IF NOT EXISTS `content` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `short_desc` text NOT NULL,
    `full_desc` text NOT NULL,
    `timestamp` int(11) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
```

3. Copy /base/db.example.php file to /base/db.php file.

4. Configure db_name, db_user and db_pass values only in the /base/db.php file.
