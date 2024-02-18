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

### Role Policy

Para garantir o acesso a `RoleResource` via `RolePolicy`, você precisa adicionar o seguinte ao seu `AuthServiceProvider`:

```php
protected $policies = [
    'Spatie\Permission\Models\Role' => 'App\Policies\RolePolicy',
];
```

Você pode pular esta etapa se tiver habilitado a partir do arquivo de configuração:

```php
'register_role_policy' => [
    'enabled' => true,
],
```

### Caminho do Policy

Se suas políticas não estiverem no diretório padrão de Políticas no app_path(), você pode alterar o nome do diretório no arquivo de configuração `filament-shield.php`:

```php
'generator' => [
    'option' => 'policies_and_permissions',
    'policy_directory' => 'Policies',
],
```

### Estrutura de Pastas Personalizada para Models ou Plugins de Terceiros

O Filament Shield também gera políticas e permissões para plugins de terceiros e Models com estrutura de pastas personalizada e para aplicar as políticas geradas, você precisará registrá-las no AuthServiceProvider da sua aplicação:

```php
class AuthServiceProvider extends ServiceProvider
{
    ...
    protected $policies = [
        ...,
        'App\Models\Blog\Author' => 'App\Policies\Blog\AuthorPolicy',
        'Ramnzys\FilamentEmailLog\Models\Email' => 'App\Policies\EmailPolicy'

    ];
}
```

### Usuários (Atribuindo Funções aos Usuários)

O Filament Shield não vem com uma forma de atribuir funções aos seus usuários por padrão, no entanto, você pode facilmente atribuir funções aos seus usuários usando o componente Select ou CheckboxList do Filament Forms. Dentro do formulário do seu Recurso de usuários, adicione um desses componentes e configure-os conforme necessário:

```php
// Usando o componente Select
Forms\Components\Select::make('roles')
    ->relationship('roles', 'name')
    ->multiple()
    ->preload()
    ->searchable()

// Usando o componente CheckboxList
Forms\Components\CheckboxList::make('roles')
    ->relationship('roles', 'name')
    ->searchable()
```

### Personalização do Layout

Você pode personalizar facilmente as colunas do Grid, Section e CheckboxList sem publicar o recurso.

```php
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

public function panel(Panel $panel): Panel
{
        return $panel
            ...
            ...
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ]);
}
```