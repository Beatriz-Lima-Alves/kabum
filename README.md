# 📌 Sistema de Gerenciamento de Clientes

Este projeto é um sistema simples de **cadastro e gerenciamento de clientes**, desenvolvido em **PHP puro** com banco de dados **MySQL**, testado no ambiente **XAMPP**.  

---

## 🚀 Tecnologias utilizadas
- PHP 
- MySQL
- XAMPP (Apache + MySQL)  
- GitHub

---

## ⚙️ Pré-requisitos
Antes de começar, certifique-se de ter instalado:
- [XAMPP](https://www.apachefriends.org/pt_br)  


## 📂 Clonar o projeto
1. Acessar o repositório
- Abra o navegador e vá para o repositório no GitHub: 
https://github.com/Beatriz-Lima-Alves/kabum

2. Clique no botão verde **Code**.  
3. Clique em **Download ZIP**.  
4. Extraia o arquivo `.zip`.  
5. Mova a pasta extraída para dentro do diretório `htdocs` do XAMPP.  
Exemplo (Windows):  
C:\xampp\htdocs\


## 🗄️ Configuração do Banco de Dados

1. Inicie o XAMPP (Apache e MySQL).
2. Acesse o phpMyAdmin em http://localhost/phpmyadmin
3. Crie o banco de dados:
Importe o arquivo database.sql que está na pasta doc
4. Configure as credenciais no arquivo config/database.php:
define('DB_HOST', 'localhost');
define('DB_NAME', 'kabum');
define('DB_USER', 'root');
define('DB_PASS', '');

## ▶️ Rodar o projeto

1. Configure o link base do seu projeto no arquivo config/database.php:
define('SITE_URL', 'http://localhost/kabum');
2. Certifique-se de que o Apache e MySQL estão ativos no XAMPP.
3. Acesse no navegador:
http://localhost/kabum

## ✅ Estrutura do Projeto
📂 kabum
 ┣ 📂 config        # Configuração geral e do banco de dados 
 ┣ 📂 controller    # Controladores da aplicação
 ┣ 📂 model         # Modelos e classes de acesso ao banco
 ┣ 📂 view          # Arquivos de interface (HTML/PHP)
 ┗ index.php        # Ponto de entrada da aplicação