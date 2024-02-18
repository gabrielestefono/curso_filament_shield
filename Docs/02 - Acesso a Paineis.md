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

Após adicionar a trait, você deve implementar o método `canAccessPanel` na model do usuário. O método deve retornar `true` se o usuário tiver acesso ao painel e `false` caso contrário. O método deve ficar assim:

```php
public function canAccessPanel(): bool
{
    return $this->hasRole(Utils::getSuperAdminName()) || $this->hasRole(Utils::getPanelUserRoleName());
}
```

No método `canAccessPanel`, estamos verificando se o usuário tem o nível de acesso `super_admin` ou qualquer outro nível de acesso que você tenha definido, caso não tenha nenhum ele não terá acesso ao painel.