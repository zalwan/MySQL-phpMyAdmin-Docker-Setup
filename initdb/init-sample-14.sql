/* 1. Membuat database management_farm_067 */ 

DROP DATABASE IF EXISTS management_farm_067;
CREATE DATABASE management_farm_067;
USE management_farm_067;

--  TABLE: farmers   
CREATE TABLE farmers (
    farmer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    contact VARCHAR(50),
    assigned_tasks TEXT
);

-- TABLE: plots
CREATE TABLE plots (
    plot_id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100),
    size DECIMAL(10,2),
    soil_type VARCHAR(50),
    farmer_id INT,
    FOREIGN KEY (farmer_id) REFERENCES farmers(farmer_id)
);

-- TABLE: plants
CREATE TABLE plants (
    plant_id INT AUTO_INCREMENT PRIMARY KEY,
    plot_id INT,
    growth_stage VARCHAR(50),
    watering_schedule VARCHAR(100),
    fertilizer_use TEXT,
    FOREIGN KEY (plot_id) REFERENCES plots(plot_id)
);

-- TABLE: fertilizers
CREATE TABLE fertilizers (
    fertilizer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    type VARCHAR(50),
    recommended_usage TEXT
);

-- TABLE: plant_fertilizers
CREATE TABLE plant_fertilizers (
    plant_id INT,
    fertilizer_id INT,
    PRIMARY KEY (plant_id, fertilizer_id),
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id),
    FOREIGN KEY (fertilizer_id) REFERENCES fertilizers(fertilizer_id)
);

-- TABLE: harvests
CREATE TABLE harvests (
    harvest_id INT AUTO_INCREMENT PRIMARY KEY,
    plot_id INT,
    date DATE,
    quantity DECIMAL(10,2),
    quality VARCHAR(50),
    farmer_id INT,
    FOREIGN KEY (plot_id) REFERENCES plots(plot_id),
    FOREIGN KEY (farmer_id) REFERENCES farmers(farmer_id)
);

-- TABLE: sales
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    harvest_id INT,
    customer VARCHAR(100),
    price DECIMAL(10,2),
    delivery_date DATE,
    FOREIGN KEY (harvest_id) REFERENCES harvests(harvest_id)
);

/* 2.Input masing-masing 5 record disetiap table */

/* farmers */
INSERT INTO farmers (name, contact, assigned_tasks) VALUES
('Budi Santoso', '081234567890', 'Planting'),
('Siti Aminah', '081234567891', 'Harvesting'),
('Agus Pratama', '081234567892', 'Soil Management'),
('Rina Lestari', '081234567893', 'Irrigation'),
('Dedi Kurniawan', '081234567894', 'Sales');

/* plots */
INSERT INTO plots (location, size, soil_type, farmer_id) VALUES
('Plot A', 2.50, 'Loamy', 1),
('Plot B', 3.00, 'Sandy', 2),
('Plot C', 1.80, 'Clay', 3),
('Plot D', 2.00, 'Peaty', 4),
('Plot E', 2.20, 'Alluvial', 5);

/* plants */
INSERT INTO plants (plot_id, growth_stage, watering_schedule, fertilizer_use) VALUES
(1, 'Seedling', 'Daily', 'Organic'),
(2, 'Vegetative', 'Every 2 days', 'NPK'),
(3, 'Flowering', 'Daily', 'Compost'),
(4, 'Fruiting', 'Every 3 days', 'Urea'),
(5, 'Harvest Ready', 'Weekly', 'None');

/* fertilizers */
INSERT INTO fertilizers (name, type, recommended_usage) VALUES
('Urea', 'Chemical', '20kg per hectare'),
('NPK', 'Chemical', '15kg per hectare'),
('Compost', 'Organic', '5kg per plant'),
('Manure', 'Organic', '10kg per plant'),
('Bio Fertilizer', 'Biological', 'Spray weekly');

/* plant_fertilizers */
INSERT INTO plant_fertilizers (plant_id, fertilizer_id) VALUES
(1, 3),
(2, 2),
(3, 4),
(4, 1),
(5, 5);

/* harvests */
INSERT INTO harvests (plot_id, date, quantity, quality, farmer_id) VALUES
(1, '2025-01-10', 500.00, 'Good', 1),
(2, '2025-01-12', 600.00, 'Excellent', 2),
(3, '2025-01-15', 450.00, 'Good', 3),
(4, '2025-01-18', 700.00, 'Average', 4),
(5, '2025-01-20', 800.00, 'Excellent', 5);

/* sales */
INSERT INTO sales (harvest_id, customer, price, delivery_date) VALUES
(1, 'PT Agro Jaya', 1500000.00, '2025-01-22'),
(2, 'CV Tani Makmur', 1800000.00, '2025-01-23'),
(3, 'UD Pangan Sehat', 1400000.00, '2025-01-24'),
(4, 'PT Food Indo', 2000000.00, '2025-01-25'),
(5, 'CV Fresh Farm', 2200000.00, '2025-01-26');


/* 3.Tampilkan seluruh data di setiap table */

SELECT * FROM farmers;
SELECT * FROM plots;
SELECT * FROM plants;
SELECT * FROM fertilizers;
SELECT * FROM plant_fertilizers;
SELECT * FROM harvests;
SELECT * FROM sales;


/* 4.Buat view (view_master) yang berisikan data plants dan plots) */
CREATE VIEW view_master AS
SELECT
    p.plant_id,
    p.growth_stage,
    p.watering_schedule,
    pl.plot_id,
    pl.location,
    pl.size,
    pl.soil_type
FROM plants p
JOIN plots pl ON p.plot_id = pl.plot_id;

SELECT * FROM view_master;
