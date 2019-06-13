<?php
require_once "app/ServiceProvider.php";
require_once "app/configs/DefaultServiceConfig.php";
require_once "app/Autoloader.php";

chdir("/vagrant/LinksStorage");
Autoloader::run();
ServiceProvider::setDefaultServices();

if ($argv[1] == "migrate") {
    migrate();
}
else if ($argv[1] == "add") {
    addMigration($argv[2]);
}
else if ($argv[1] == "remove") {
    removeMigration($argv[2]);
}
else if ($argv[1] == "create") {
    createDatabase();
}

function createDatabase() {
    $config = new Config();
    $host = $config->getDbHost();
    $login = $config->getDbLogin();
    $password = $config->getDbPassword();
    $database = $config->getDbName();
    $pdo = @new PDO(
        "mysql:host=$host",
        $login,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $pdo->prepare("CREATE DATABASE IF NOT EXISTS $database");
    $sql->execute();
    $sql = $pdo->prepare("USE $database");
    $sql->execute();

    $sql = $pdo->prepare("CREATE TABLE IF NOT EXISTS `links` (
      `id` int(11) NOT NULL,
      `userId` int(11) NOT NULL,
      `title` varchar(100) NOT NULL,
      `description` varchar(3000) NOT NULL,
      `url` varchar(2083) NOT NULL,
      `private` tinyint(1) NOT NULL DEFAULT '0',
      `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $pdo->prepare("CREATE TABLE IF NOT EXISTS`migrations` (
      `id` int(11) NOT NULL,
      `name` varchar(50) NOT NULL,
      `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $pdo->prepare("CREATE TABLE IF NOT EXISTS`rolePermissions` (
      `id` int(11) NOT NULL,
      `roleId` int(11) NOT NULL,
      `target` varchar(50) NOT NULL,
      `operation` varchar(50) NOT NULL,
      `access_level` varchar(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $pdo->prepare("INSERT INTO `rolePermissions` (`id`, `roleId`, `target`, `operation`, `access_level`) VALUES
        (1, 1, 'user', 'index', ''),
        (2, 1, 'user', 'edit', 'any'),
        (5, 1, 'user', 'index', ''),
        (6, 1, 'user', 'delete', 'any'),
        (7, 1, 'link', 'create', ''),
        (8, 1, 'link', 'edit', 'any'),
        (9, 1, 'link', 'read', 'any'),
        (10, 1, 'link', 'delete', 'any'),
        (11, 1, 'link', 'index', ''),
        (16, 2, 'link', 'create', ''),
        (17, 2, 'link', 'edit', 'any'),
        (18, 2, 'link', 'read', 'any'),
        (19, 2, 'link', 'delete', 'any'),
        (20, 3, 'link', 'create', ''),
        (21, 3, 'link', 'edit', 'own'),
        (22, 3, 'link', 'read', 'own'),
        (23, 3, 'link', 'delete', 'own'),
        (24, 4, 'link', 'read', 'own'),
        (29, 2, 'user', 'edit', 'own'),
        (30, 2, 'user', 'read', 'own'),
        (31, 3, 'user', 'edit', 'own'),
        (32, 3, 'user', 'read', 'own'),
        (37, 1, 'logout', 'index', ''),
        (38, 1, 'error', 'error404', ''),
        (39, 1, 'error', 'error403', ''),
        (40, 2, 'logout', 'index', ''),
        (41, 2, 'error', 'error403', ''),
        (42, 2, 'error', 'error404', ''),
        (43, 3, 'logout', 'index', ''),
        (44, 3, 'error', 'error404', ''),
        (45, 3, 'error', 'error403', ''),
        (46, 2, 'link', 'index', ''),
        (47, 1, 'link', 'showList', ''),
        (49, 3, 'link', 'index', ''),
        (51, 4, 'link', 'index', ''),
        (52, 4, 'error', 'error404', ''),
        (53, 4, 'error', 'error403', ''),
        (54, 4, 'register', 'index', ''),
        (55, 4, 'login', 'index', ''),
        (56, 4, 'verify', 'index', ''),
        (57, 4, 'logout', 'index', ''),
        (58, 4, 'login', 'sendlink', ''),
        (59, 2, 'link', 'showList', ''),
        (60, 3, 'link', 'showList', ''),
        (61, 4, 'link', 'showList', ''),
        (62, 1, 'link', 'readAjax', 'any'),
        (63, 1, 'link', 'editAjax', 'any'),
        (64, 2, 'link', 'editAjax', 'any'),
        (65, 3, 'link', 'editAjax', 'own'),
        (66, 2, 'link', 'readAjax', 'any'),
        (67, 3, 'link', 'readAjax', 'own'),
        (68, 4, 'link', 'readAjax', 'own');
        ");
    $sql->execute();

    $sql = $pdo->prepare("CREATE TABLE IF NOT EXISTS `roles` (
      `id` int(11) NOT NULL,
      `name` varchar(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $pdo->prepare("INSERT INTO `roles` (`id`, `name`) VALUES
        (1, 'admin'),
        (2, 'editor'),
        (3, 'user'),
        (4, 'anonymous');");
    $sql->execute();

    $sql = $pdo->prepare("CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL,
      `login` varchar(100) NOT NULL,
      `firstName` varchar(100) NOT NULL,
      `lastName` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `roleId` int(11) NOT NULL DEFAULT '3',
      `password` varchar(100) NOT NULL,
      `verificationText` varchar(100) NOT NULL,
      `approved` tinyint(1) NOT NULL DEFAULT '0',
      `verificationCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $sql->execute();

    $sql = $pdo->prepare("INSERT INTO `users` (`id`, `login`, `firstName`, `lastName`, `email`, `roleId`, `password`, `verificationText`, `approved`, `verificationCreated`) VALUES
    (1, 'Anonymous', 'Anonymous ', 'User', 'no@mail.mail', 4, '', '', 1, '2019-05-31 07:57:46');");
    $sql->execute();

    $sql = $pdo->prepare("ALTER TABLE `links`
        ADD PRIMARY KEY (`id`),
        ADD KEY `user_id` (`userId`),
        ADD KEY `user_id_2` (`userId`);
        
        ALTER TABLE `migrations`
        ADD PRIMARY KEY (`id`);        
        
        ALTER TABLE `rolePermissions`
        ADD PRIMARY KEY (`id`),
        ADD KEY `roleId` (`roleId`);        
        
        ALTER TABLE `roles`
        ADD PRIMARY KEY (`id`);        
        
        ALTER TABLE `users`
        ADD PRIMARY KEY (`id`),
        ADD KEY `id` (`id`),
        ADD KEY `role` (`roleId`);");
    $sql->execute();

    $sql = $pdo->prepare("
        ALTER TABLE `links`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
        ALTER TABLE `migrations`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
        ALTER TABLE `rolePermissions`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
        ALTER TABLE `roles`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
        ALTER TABLE `users`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");
    $sql->execute();

    $sql = $pdo->prepare("ALTER TABLE `links`
        ADD CONSTRAINT `links_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);        
        
        ALTER TABLE `rolePermissions`
        ADD CONSTRAINT `rolePermissions_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`);
       
        ALTER TABLE `users`
        ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`);");
    $sql->execute();
}

function migrate() {
    $database = ServiceProvider::getService("Database");
    $pdo = $database->getConnection();
    $statement = $pdo->prepare("SELECT * FROM migrations ORDER BY date_created ASC");
    $statement->execute();
    $migrations = $statement->fetchAll();
    foreach($migrations as $migrationName) {
        $migration = new $migrationName["name"];
        $migration->up();
    }
    $statement = $pdo->prepare("DELETE FROM migrations");
    $statement->execute();
}

function addMigration($migrationName) {
    $database = ServiceProvider::getService("Database");
    $pdo = $database->getConnection();
    $statement = $pdo->prepare("INSERT INTO migrations (name) VALUES (:migrationName)");
    $data = array("migrationName"=>$migrationName);
    $statement->execute($data);
}

function removeMigration($migrationName) {
    $database = ServiceProvider::getService("Database");
    $pdo = $database->getConnection();
    $statement = $pdo->prepare("DELETE FROM migrations WHERE name = :migrationName");
    $data = array("migrationName"=>$migrationName);
    $statement->execute($data);

}