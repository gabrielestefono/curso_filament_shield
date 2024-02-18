# 05 - Filament Shield - Permissão de Acesso aos Widgets

Se você gerou permissões para Widgets, você pode alternar o estado deles com base se um usuário tem permissão ou não. Você pode configurar isso manualmente, mas este pacote vem com um trait HasWidgetShield para acelerar esse processo. Tudo o que você precisa fazer é usar o trait em seus widgets:

```php
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class IncomeWidget extends LineChartWidget
{
    use HasWidgetShield;
    ...
}
```

Simples assim! Logicamente, assim como HasPageShield, HasWidgetShield usa o método `discoverWidgets` para descobrir todos os widgets que usam o trait e adiciona as permissões automaticamente. Então, os widgets que usam HasWidgetShield precisam estar dentro do diretório `app/Filament/Widgets` para serem descobertos.

## Mãos a Obra

No nosso caso, temos 2 widgets que queremos proteger: `LatestStudents` e `StatsOverview`. Vamos adicionar o trait `HasWidgetShield` em ambos os widgets:

```php
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class StatsOverview extends BaseWidget
{
    use HasWidgetShield;
	...
}
```

```php
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class LatestStudents extends BaseWidget
{
	use HasWidgetShield;
	...
}
```

Pronto! Agora, se você tentar acessar a página de widgets sem permissão, você verá que os widgets estão ocultos. Se você tiver permissão, você verá os widgets normalmente.