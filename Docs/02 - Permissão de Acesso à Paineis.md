# 02 - Filament Shield - Permissão de Acesso à Paineis

Observação: Se você quiser habilitar o Shield para mais de um painel, você precisa registrar o plugin para cada painel que você deseja habilitar.

## Limitando o Acesso a um Painel

O plugin Shield vem com uma trait chamada `HasPanelShield` que te dá uma forma fácil de integrar o Shield com o painel.

A trait `HasPanelShield` fornece a implementação de um método chamado `canAccessPanel` que determina se o usuário tem acesso se o usuário possui o nível de acesso `super_admin` ou `panel_access`. Claro que os nomes dos níveis de acesso podem ser mudados.

Para fazer isso é simples, acessa a model do usuário no nosso caso, `User` e adicione a trait `HasPanelShield`. O código deve ficar assim:

```php
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;
}
```

