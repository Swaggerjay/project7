-- migrations/add_product_details.sql
-- Run this to update the DB for the new detailed product pages.

ALTER TABLE `products` 
ADD COLUMN `category` VARCHAR(50) DEFAULT 'General' AFTER `name`,
ADD COLUMN `description` TEXT DEFAULT NULL AFTER `price`,
ADD COLUMN `specifications` JSON DEFAULT NULL AFTER `description`;

-- Seed some detailed data for existing products
UPDATE `products` SET 
category = 'Sheer',
description = 'Our Ivory Sheer Weave curtains are designed to let in natural light while maintaining your privacy. The delicate texture adds a touch of elegance to any room.',
specifications = '{"Material": "Linen Blend", "Transparency": "Sheer", "Care": "Machine Washable", "Style": "Modern"}'
WHERE name LIKE '%Sheer%';

UPDATE `products` SET 
category = 'Blackout',
description = 'Experience total darkness and thermal insulation with our Mocha Blackout curtains. Perfect for bedrooms and home theaters.',
specifications = '{"Material": "Premium Polyester", "Transparency": "Blackout", "Care": "Dry Clean Recommended", "Style": "Classic"}'
WHERE name LIKE '%Blackout%' OR name LIKE '%Onyx%';

UPDATE `products` SET 
category = 'Velvet',
description = 'Luxury meets comfort with our Champagne Velvet drapes. The heavy fabric provides excellent insulation and a rich, deep aesthetic.',
specifications = '{"Material": "Premium Velvet", "Transparency": "Room Darkening", "Care": "Professional Clean", "Style": "Luxury"}'
WHERE name LIKE '%Velvet%';

UPDATE `products` SET 
category = 'Silk',
description = 'Hand-finished silk drapes that shimmer in the light. A true statement piece for high-end dining and living rooms.',
specifications = '{"Material": "Pure Silk", "Transparency": "Medium", "Care": "Dry Clean Only", "Style": "Elegant"}'
WHERE name LIKE '%Silk%';
