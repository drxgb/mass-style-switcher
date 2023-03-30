# Mass Style Switcher

### Desenvolvido por Dr.XGB

<hr />

Pequena aplicação via terminal feita em PHP para forçar uma modificação de um estilo a uma massa de usuários em um Banco de Dados. Esta aplicação possuem 3 simples funções:

-   Armazenar
-   Carregar
-   Forçar alteração de um estilo

Você pode armazenar as informações de um banco de dados a um arquivo `.json` e carregar esse mesmo arquivo (ou outro) e aplicar ao banco de dados. Além disso, é possível forçar a alteração de um único estilo para todos os usuários.

Para configurar a aplicação para que funcione em seu banco de dados, temos o arquivo `config.php`, onde você pode fazer as alterações necessárias para estabelecer uma conexão entre sua aplicação e o seu banco de dados. Neste arquivo, por exemplo, foi utilizado o ambiente Docker com as imagens do **PHP com Apache** e **MySQL**. Portanto o arquivo foi adaptado para rodar em um container. Para isso, você vai precisar configurar seu `DockerFile` e/ou seu `docker-compose.yaml` para que a aplicação funcionae corretamente.
Caso você não tenha ou não deseja usar o Docker, basta definir o **host** para `localhost` e **port** para o número da porta que o seu servidor local utiliza em sua máquina.
