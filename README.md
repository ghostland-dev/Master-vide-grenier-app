# Vide Grenier en Ligne

This README.md is intended for future maintainers of the Vide Grenier en Ligne website.

## Setting Up the Backend Project

1. **Create a VirtualHost**: Point it to the `/public` directory of the website (using Apache).
2. **Import the Database**: Use the provided MySQL file (`sql/import.sql`) to set up the database.
3. **Connect the Project to the Database**: Configure the connection settings in the configuration files.
4. **Install Dependencies**: Run the following command to install the necessary dependencies:
   ```sh
   composer install
   ```

## Setting Up the Frontend Project

1. **Install Node-Sass**: Run the following command to install node-sass:
   ```sh
   npm install
   ```
2. **Compile SCSS Files**: Run the following command to watch and compile SCSS files:
   ```sh
   npm run watch
   ```

## Routing

The Router translates URLs.

Routes are added via the `add` method.

In addition to specifying controllers and actions, you can specify a parameter like in the following route:
```php
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
```

## Views

Views are rendered using Twig. You can find the views in the `App/Views` directory.

Example:
```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Toto',
    'colours' => ['red', 'blue', 'green']
]);
```

## Models

Models are used to retrieve or store data within the application. Models inherit from `Core\Model` and use PDO for database access.

Example:
```php
$db = static::getDB();
```

## Author

**Ismail.M**, Software Engineer & DevOps

- GitHub: [@ghostland-dev](https://github.com/ghostland-dev)
- Website: [ismail-mouyahada.com](https://www.ismail-mouyahada.com)
