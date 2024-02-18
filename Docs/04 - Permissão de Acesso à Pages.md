# 04 - Filament Shield - Permissão de Acesso à Pages

Se você gerou permissões para Pages, você pode alternar a navegação da página na barra lateral e o acesso restrito à página. Você pode configurar isso manualmente, mas este pacote vem com um trait HasPageShield para acelerar esse processo. Tudo o que você precisa fazer é usar o trait em suas páginas:

```php
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyPage extends Page
{
    use HasPageShield;
    ...
}
```

HasPageShield usa o método booted para verificar as permissões do usuário e garante executar o método da página boot no pai se existir.


## Páginas Hooks

No entanto, se você precisar executar alguns métodos antes e depois do método boot, você pode declarar os métodos de hooks a seguir em sua página de filamento.

```php
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyPage extends Page
{
    use HasPageShield;

    protected function beforeBooted : void() {
        ...
    }

    protected function afterBooted : void() {
        ...
    }

    /**
     * Hook to perform an action before redirect if the user
     * doesn't have access to the page.  
     * */
    protected function beforeShieldRedirects : void() {
        ...
    }
}
```

## Páginas Redirect Path

HasPageShield usa o valor config('filament.path') por padrão para realizar a redireção do Shield. Se você precisar sobrescrever o caminho de redireção, basta adicionar o próximo método à sua página:

```php
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyPage extends Page
{
	use HasPageShield;
	...

	protected function getShieldRedirectPath(): string {
		return '/';
	}
}
```

## Observação Importante

As páginas devem ser criadas em uma pasta diferente e separada das pasta `resources` e `widgets` para que funcionem corretamente. Isso ocorre porque o `FilamentShield` usa o método `discoverPages` do arquivo `AdminPanelProvider` para descobrir as páginas e aplicar o `HasPageShield` a elas. Se você criar suas páginas na pasta `resources`, o `FilamentShield` não será capaz de descobri-las e, caso você adicione a pasta manualmente, devido à chamada das páginas, ocorrera um erro de `Array to string conversion`.

## Considerações

Por hora, não tenho nenhuma page no código para testar, mas acredito que o código está correto. Lembrar de criar uma página depois e testar o funcionamento do `HasPageShield`.