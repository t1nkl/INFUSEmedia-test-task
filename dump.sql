CREATE TABLE banner_views
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    ip_address  VARCHAR(39)  NOT NULL,
    user_agent  VARCHAR(350)  NOT NULL,
    page_url    VARCHAR(350)  NOT NULL,
    view_date   DATETIME     NOT NULL,
    views_count INT UNSIGNED NOT NULL DEFAULT 1,

    CONSTRAINT unique_views UNIQUE (ip_address, user_agent, page_url, view_date)
);

CREATE INDEX idx_banner_views_combined ON banner_views (ip_address, user_agent, page_url, view_date);

INSERT INTO banner_views (ip_address, user_agent, page_url, view_date, views_count)
VALUES ('127.0.0.1',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36',
        '/index1.html', '2023-04-21 22:39:37', 1)
