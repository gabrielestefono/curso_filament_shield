# 01 - Filament Shield - Instalação

## Introdução

O Filament Shield é uma forma facilitada de implementar permissões de acesso de acordo com as regra de negócio do seu sistema. Ele é uma camada de segurança que permite que você defina regras de acesso de acordo com o perfil do usuário. 

## Instalação

### Instalação do pacote via composer

Para instalar o Filament Shield, basta no seu terminal, rodar o comando:

```bash
composer require bezhansalleh/filament-shield
```

### Adicionando a trait ao model User

Para que o Filament Shield funcione corretamente, é necessário adicionar a trait `HasRoles` ao model User. 

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	use HasRoles;
}
```

### Publicando as configurações

Para publicar as configurações do Filament Shield, basta rodar o comando:

```bash
php artisan vendor:publish --tag=filament-shield-config
```

### Registrando o plugin para os painéis do Filament que deseja proteger

#### O que significa registrar o plugin?

O Filament Shield é um plugin que precisa para o Filament. O Filament por sua vez, é um framework que permite a criação de painéis administrativos e que tem suporte a vários plugins. Para que o Filament Shield funcione corretamente, é necessário registrar o plugin para os painéis que deseja proteger.

#### Como registrar o plugin?

Para registrar o plugin, é necessário acessar o arquivo acessar o seu arquivo de configurações do painel do filament, que por padrão fica localizado em `App\Providers\Filament\AdminPanelProvider.php`, ao final do arquivo, basta adicionar o seguinte código:

```php
->plugins([
	\BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
]);
```

O código completo deve ficar assim:

```php
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ... // Outras configurações
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ]);
    }
}
```

### Instalando o Filament Shield

Para instalar o Filament Shield, basta rodar o comando:

```bash
php artisan shield:install
```