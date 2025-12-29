-- Add description column to products table
USE fast_food;

ALTER TABLE products ADD COLUMN description TEXT AFTER price;