CREATE TABLE food_product (
    sid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    shop_list_sid INT,
    product_code VARCHAR(255) NOT NULL,
    product_categories VARCHAR(255) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_description TEXT NOT NULL,
    unit_price VARCHAR(255) NOT NULL,
    sale_price VARCHAR(255) NOT NULL,
    product_sequence INT NOT NULL,
    shop_deadline INT,
    created_at DATETIME NOT NULL,
    INDEX(product_code),
    FULLTEXT(product_name),
    FULLTEXT(product_categories),
    FULLTEXT(product_categories),
    FULLTEXT(sale_price),
    FOREIGN KEY (shop_list_sid) INT NOT NULL REFERENCES food_product(sid)
    );

ALTER TABLE food_product 
ADD COLUMN food_store_sid INT AFTER sid,
ADD COLUMN shop_list_sid INT NOT NULL AFTER sale_price,
ADD FOREIGN KEY (shop_list_sid) REFERENCES food_store(sid);

CREATE TABLE picture (
    sid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    picture_url VARCHAR(255)
    );

CREATE TABLE food_picture (
    sid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    food_product_sid INT NOT NULL FOREIGN KEY REFERENCES food_product(sid),
    picture_sid FOREIGN KEY REFERENCES picture(sid),
    seq INT NOT NULL
    ); --mysql不支援此格式

CREATE TABLE food_picture (
    sid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    food_product_sid INT,
    picture_sid INT NOT NULL,
    seq INT NOT NULL,
    FOREIGN KEY picture_sid REFERENCES picture(sid),
    FOREIGN KEY (food_product_sid) REFERENCES food_product(sid)
    --(picture_sid)自己table欄位；picture(sid)別人table欄位
    );

CREATE TABLE food_product_transaction (
    sid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    transaction_type VARCHAR(255), 
    transaction_quantity INT,
    transaction_date DATETIME,
    transaction_note VARCHAR(255), 
    food_product_sid INT NOT NULL,
    FOREIGN KEY (food_product_sid) REFERENCES food_product(sid)
    );

--建立欄位連結
SELECT `food_product`.*, `shop_list`.`shop_deadline`
    FROM `food_product`
    JOIN `shop_list` 
    ON `food_product`.`sid`=`shop_list`.`sid`


--建立產品
INSERT INTO `product_food`(`product_code`, `product_name`, `product_description`, `unit_price`, `sale_price`)  VALUES
('01-0001', 'Kobee cafe_肉桂捲', '<h3>每日新鮮製作的肉桂捲</h3>',100,60),
('01-0002', 'Kobee cafe_胡蘿蔔蛋糕', '一般咖啡廳很少見的',100,60);

--建立照片資料
INSERT INTO `picture` (picture_url) VALUES 
('pictures/IMG_1623.JPG'),
('pictures/IMG_0110-3.jpg'),
('IMG_0284-3.jpg'),
('pictures/IMG_0107.JPG');

--建立產品照片關聯
INSERT INTO `food_picture`
(food_product_sid, picture_sid, seq) VALUES
(1, 2, 1), 
(2, 3, 1),
(2, 1, 2);

--建立產品與商店關聯
INSERT INTO food_store


--上架、下架產品
INSERT INTO `food_product_transaction`
(transaction_type, transaction_quantity, transaction_date, food_product_sid) VALUES
('入庫', 10 , '2022-09-10 13:48', 1),
('出庫', -1 , '2022-09-10 20:48', 1),
('出庫', -2 , '2022-09-10 21:30', 1);


--查詢產品資料
SELECT product_code, product_name, product_description, picture_url
FROM food_product
LEFT JOIN food_picture on food_product_sid=food_product.sid
LEFT JOIN picture on picture.sid=picture_sid
ORDER BY product_code, seq

--查詢商品數量
SELECT product_code, product_name, sum(transaction_quantity)
FROM food_product
LEFT JOIN food_product_transaction on food_product_sid=food_product.sid
GROUP BY product_code, product_name
having sum(transaction_quantity) > 0