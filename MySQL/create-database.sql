DROP DATABASE IF EXISTS Entertainment;
CREATE DATABASE Entertainment;
USE Entertainment;

CREATE TABLE actor (
  actor_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  last_update DATETIME
);

CREATE TABLE category (
  category_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  last_update DATETIME
);

CREATE TABLE language (
  language_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  last_update DATETIME
);

CREATE TABLE country (
  country_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  country VARCHAR(50) NOT NULL,
  last_update DATETIME
);

CREATE TABLE city (
    city_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR(50) NOT NULL,
    country_id INTEGER NOT NULL,
    last_update DATETIME,
    
    CONSTRAINT city_country_country_id
        FOREIGN KEY (country_id) REFERENCES country(country_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE address (
  address_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  address VARCHAR(255) NOT NULL,
  address2 VARCHAR(255),
  district VARCHAR(255) NOT NULL,
  city_id INTEGER,
  postal_code INTEGER(5),
  phone VARCHAR(15),
  last_update DATETIME,
  
  CONSTRAINT address_city_city_id
      FOREIGN KEY (city_id) REFERENCES city(city_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE film (
  film_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(225) NOT NULL,
  description VARCHAR(255),
  release_year YEAR,
  language_id INTEGER,
  original_language_id SMALLINT UNSIGNED,
  rental_duration SMALLINT UNSIGNED NOT NULL,
  rental_rate DECIMAL(10,2) UNSIGNED NOT NULL,
  length SMALLINT UNSIGNED,
  replacement_cost DECIMAL(10,2) UNSIGNED NOT NULL,
  rating VARCHAR(5),
  special_features VARCHAR(255),
  last_update DATETIME,
  
  CONSTRAINT film_language_id_language
      FOREIGN KEY (language_id) REFERENCES language(language_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE film_category (
  film_id INTEGER AUTO_INCREMENT,
  category_id INTEGER,
  
  last_update DATETIME,
  
  PRIMARY KEY(film_id, category_id),
  
  CONSTRAINT film_category_film_id_film_id
      FOREIGN KEY (film_id) REFERENCES film(film_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT,
      
  CONSTRAINT film_category_category_category_id
      FOREIGN KEY (category_id) REFERENCES category(category_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE film_actor (
  actor_id INTEGER,
  film_id INTEGER,
  last_update DATETIME,
  
  PRIMARY KEY(actor_id, film_id),
  
  CONSTRAINT film_actor_actor_actor_id
      FOREIGN KEY (actor_id) REFERENCES actor(actor_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT,
      
  CONSTRAINT film_id_film_film_id
      FOREIGN KEY (film_id) REFERENCES film(film_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE store (
	store_id INTEGER PRIMARY KEY AUTO_INCREMENT,
	address_id INTEGER,
    manager_staff_id INTEGER,
	last_update DATETIME,
	
    CONSTRAINT store_address_address_id
		FOREIGN KEY (address_id) REFERENCES address(address_id)
		ON UPDATE CASCADE
		ON DELETE RESTRICT
);

CREATE TABLE staff (
  staff_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  address_id INTEGER,
  picture VARCHAR(255),
  email VARCHAR(100),
  store_id INTEGER,
  active BOOLEAN NOT NULL DEFAULT TRUE,
  -- wanted to make username and password NOT NULL but employee 'Jon' has a NULL password
  username VARCHAR(50),
  password VARCHAR(50),
  last_update DATETIME,
  
  CONSTRAINT staff_address_address_id
      FOREIGN KEY (address_id) REFERENCES address(address_id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE inventory (
  inventory_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  film_id INTEGER,
  store_id INTEGER,
  last_update DATETIME,
  
  CONSTRAINT inventory_film_film_id
        FOREIGN KEY (film_id) REFERENCES film(film_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
  CONSTRAINT inventory_store_store_id
        FOREIGN KEY (store_id) REFERENCES store(store_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE customer (
  customer_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  store_id INTEGER,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100),
  address_id INTEGER,
  active BOOLEAN NOT NULL DEFAULT 1,
  create_date DATETIME NOT NULL DEFAULT (CURDATE()),
  last_update DATETIME,
  
  CONSTRAINT customer_store_store_id
        FOREIGN KEY (store_id) REFERENCES store(store_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
  CONSTRAINT customer_address_address_id
        FOREIGN KEY (address_id) REFERENCES address(address_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE rental (
  rental_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  rental_date DATETIME,
  inventory_id INTEGER,
  customer_id INTEGER,
  return_date DATETIME,
  staff_id INTEGER,
  last_update DATETIME,
  
  CONSTRAINT rental_inventory_inventory_id
        FOREIGN KEY (inventory_id) REFERENCES inventory(inventory_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
  CONSTRAINT rental_customer_customer_id
        FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
  CONSTRAINT rental_staff_staff_id
        FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE payment (
  payment_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  customer_id INTEGER,
  staff_id INTEGER,
  rental_id INTEGER,
  amount DECIMAL(10,2) UNSIGNED NOT NULL,
  payment_date DATETIME,
  last_update DATETIME,
  
  CONSTRAINT payment_customer_customer_id
        FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
  CONSTRAINT payment_staff_staff_id
        FOREIGN KEY (staff_id) REFERENCES staff(staff_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
        
  CONSTRAINT payment_rental_rental_id
        FOREIGN KEY (rental_id) REFERENCES rental(rental_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);