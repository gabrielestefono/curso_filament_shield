# 03 - Permissão de Acesso aos Resources

De forma geral, existem 2 cenários para aplicação de permissões de acesso dos resources:

## 01 - O Método `Padrão`:

De modo geral, o Filament Shield tem algumas permissões padrões já aplicadas para os resources, então, se isso é tudo o que você precisa, você não precisa fazer nada. Se você precisar adicionar uma so permissão adicional, por exemplo `lock`, e ela está disponível para todos os resources, só necessita adicionar ela nas suas configurações (lembrando que as configurções padrões estão no arquivo `config/filament-shield.php`), da seguinte forma:

```php
 'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'lock'
        ],
        ...
    ],
```

## 02 - O Método `Customizado`:

Se você precisar de permissões customizadas para um resource, a coisa muda um pouco de cenário.

Para definir uma permissão customizada para um resource, você deve implementar a trait `HasShieldPermissions` na sua resource.

A trait `HasShieldPermissions` tem um método `getPermissionPrefixes()` que retorna um array com as permissões que você deseja adicionar para o resource. 

Vamos considerar que você tem uma resource chamada `StudentResource`, que atualmente possui algumas permissões setadas, você quer adicionar a permissão `export_student`, que só estará disponível para essa resource.

Na resource `StudentResource`, você deve implementar a trait `HasShieldPermissions`:

```php
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

/* Implements HasShieldPermissions */
class StudentResource extends Resource implements HasShieldPermissions
{}
```

Feito isso, você deve setar o método `getPermissionPrefixes()` na resource `StudentResource`:

```php
class StudentResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'export'
        ];
    }
}
```

Agora, para cumprir com a permissão, você deve ir deve criar um novo método em `Policy` para restringir o acesso a essa permissão. O arquivo policy se encontra no diretório `app/Policies/StudentPolicy.php`, e você deve adicionar a o método `export` ao final do arquivo, sendo específico quanto à model, para evitar conflitos com futuras nomeações:

```php
public function export(User $user)
{
	return $user->can('export_student');
} 
```

E por fim, você deve adicionar a permissão `export_student` no arquivo que deseja limitar as permissões, no meu caso, quero que não seja possível fazer o download de PDF de Students, então na resource `StudentResource`, que contém a Action `downloadPdf`, eu adiciono o seguinte código:

```php
// Se o usuário não tiver a permissão export_student, retorna um erro 403
if(!User::find(Auth::id())->can('export_student')) {
    return abort(403);
}
```

Assim, se o usuário não tiver a permissão `export_student`, a página retornará um erro 403. Logicamente, há melhores formas de lidar com isso, um exemplo é adicionar o método hiden na action com uma função de callback que verifica se o usuário tem a permissão, e se não tiver, esconde o botão de download, por exemplo:

```php
->hidden(function(){
    return !User::find(Auth::id())->can('export_student');
})
```

Assim, caso o usuário não tenha a permissão downloadPDF, o botão de download não será exibido.

Observação: Normalmente, o método Padrao vem com todas as permissões que você precisa, caso você precise de uma permissão customizada, recomendo copiar o método `getPermissionPrefixes()` do arquivo `config/filament-shield.php` e adicionar a permissão que você precisa.

## 03 - Traduzindo as Permissões:

Para traduzir as permissões, primeiro é necessário publicar o arquivo de tradução do Filament Shield, para fazer isso, execute o seguinte comando no seu terminal:

```bash
php artisan vendor:publish --tag=filament-shield-translations
```

Agora, dentro do diretório `lang\filament-shield\{locale}\filament-shield.php`, você pode traduzir as permissões para o idioma que desejar. Substitua o valor das chaves pelo que deseja, por exemplo `en` para inglês, `pt` para português, `pt_BR` para português do Brasil, e assim por diante. Neste arquivo, você encontrará um array com nome de `resource_permission_prefixes_labels`, onde cada chave é o nome da permissão, e o valor é a tradução da permissão, por exemplo:

```php
'resource_permission_prefixes_labels' => [
    'view' => 'Visualizar',
    'view_any' => 'Visualizar Qualquer',
    'create' => 'Criar',
    'update' => 'Atualizar',
    'restore' => 'Restaurar',
    'restore_any' => 'Restaurar Qualquer',
    'replicate' => 'Replicar',
    'reorder' => 'Reordenar',
    'delete' => 'Deletar',
    'delete_any' => 'Deletar Qualquer',
    'force_delete' => 'Deletar Permanentemente',
    'force_delete_any' => 'Deletar Permanentemente Qualquer',
    'lock' => 'Bloquear',
    'export' => 'Exportar'
],
```

## 04 - Configurando o Identificador de Permissão:

Por padrão, o identificador de permissão é gerado da seguinte forma:

```php
Str::of($resource)
    ->afterLast('Resources\\')
    ->before('Resource')
    ->replace('\\', '')
    ->snake()
    ->replace('_', '::');
```

Então, por exemplo, se você tem um resource como `App\Filament\Resources\Shop\CategoryResource`, então o identificador de permissão seria `shop::category`, e então seria prefixado com os prefixos definidos ou o que vem por padrão com o shield.

Se você deseja alterar o comportamento padrão, pode chamar o método estático `configurePermissionIdentifierUsing()` dentro do método `boot()` de um `ServiceProvider`, para o qual você passa um Closure para modificar a lógica. O Closure recebe o nome da classe totalmente qualificado do resource como `$resource`, o que lhe dá a capacidade de acessar qualquer propriedade ou método definido dentro do resource.

Por exemplo, se você deseja usar o nome do modelo como identificador de permissão, pode fazer assim:

```php
use BezhanSalleh\FilamentShield\Facades\FilamentShield;

FilamentShield::configurePermissionIdentifierUsing(
    fn($resource) => str($resource::getModel())
        ->afterLast('\\')
        ->lower()
        ->toString()
);
```

## 05 - Configurando o Grupo de Navegação Personalizado:

Por padrão, a tradução em inglês renderiza Roles and Permissions sob 'Filament Shield', se você deseja alterar isso, primeiro publique os arquivos de tradução e altere o locale relativo ao grupo de sua escolha, por exemplo:

`'nav.group' => 'Filament Shield'`,

para

`'nav.group' => 'User Management'`,

aplique isso a cada idioma que você tem grupos.