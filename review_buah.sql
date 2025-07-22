-- Tabel review/rating buah
CREATE TABLE IF NOT EXISTS review_buah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buah_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review TEXT,
    tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (buah_id, user_id),
    FOREIGN KEY (buah_id) REFERENCES buah(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
--
-- Untuk integrasi: import ke database Anda
