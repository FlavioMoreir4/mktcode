<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $modelLabel = 'Mensagem';

    protected static ?string $pluralModelLabel = 'Mensagens';

    protected static ?string $navigationLabel = 'Mensagens';

    // 🔥 Badge no menu = radar de oportunidade
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'new')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Mensagem recebida')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->columnSpan(8)
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->rows(12)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Grid::make(1)
                    ->columnSpan(4)
                    ->schema([
                        Group::make()
                            ->schema([

                                Section::make('Contato')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->disabled(),
                                        Forms\Components\TextInput::make('email')->disabled(),
                                        Forms\Components\TextInput::make('whatsapp')->disabled(),
                                    ]),

                                Section::make('Gestão')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'new' => 'Novo',
                                                'in_progress' => 'Em atendimento',
                                                'resolved' => 'Resolvido',
                                                'archived' => 'Arquivado',
                                            ])
                                            ->default('new')
                                            ->native(false),

                                        Forms\Components\Textarea::make('notes')
                                            ->rows(4),
                                    ]),

                                Section::make('Registro')
                                    ->compact()
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('Recebido')
                                            ->since(),

                                        TextEntry::make('updated_at')
                                            ->label('Atualizado')
                                            ->since(),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            // 🔥 Prioriza automaticamente mensagens novas
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByRaw("CASE WHEN status = 'new' THEN 0 ELSE 1 END")
            )

            ->columns([

                Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label('Contato')
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->searchable(),

                    Tables\Columns\TextColumn::make('email')
                        ->size(TextSize::Small)
                        ->color('gray')
                        ->copyable(),
                ]),

                Tables\Columns\TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->message)
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'in_progress' => 'warning',
                        'resolved' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Novo',
                        'in_progress' => 'Em atendimento',
                        'resolved' => 'Resolvido',
                        'archived' => 'Arquivado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recebido')
                    ->since()
                    ->sortable(),
            ])

            // 🔥 Destaque visual para novos
            ->recordClasses(fn (Inquiry $record) => $record->status === 'new'
                    ? 'bg-gray-50 dark:bg-gray-800/40'
                    : null
            )

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'Novo',
                        'in_progress' => 'Em atendimento',
                        'resolved' => 'Resolvido',
                        'archived' => 'Arquivado',
                    ]),
            ])

            ->recordActions([
                ActionGroup::make([

                    // 🔥 Ação rápida = reduz fricção absurda
                    Action::make('atender')
                        ->label('Atender')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->visible(fn (Inquiry $record) => $record->status === 'new')
                        ->action(fn (Inquiry $record) => $record->update(['status' => 'in_progress'])),

                    Action::make('resolver')
                        ->label('Resolver')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->visible(fn (Inquiry $record) => $record->status !== 'resolved')
                        ->action(fn (Inquiry $record) => $record->update(['status' => 'resolved'])),

                    ViewAction::make(),
                    EditAction::make(),

                    Action::make('whatsapp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn (Inquiry $record): string => 'https://wa.me/'.preg_replace('/\D/', '', $record->whatsapp))
                        ->openUrlInNewTab(),

                    Action::make('email')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->url(fn (Inquiry $record): string => "mailto:{$record->email}")
                        ->openUrlInNewTab(),
                ]),
            ])

            ->groupedBulkActions([
                BulkActionGroup::make([

                    BulkAction::make('marcar_como_resolvido')
                        ->label('Marcar como resolvido')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['status' => 'resolved'])),

                    DeleteBulkAction::make(),
                ]),
            ])

            ->emptyStateHeading('Nenhuma mensagem ainda')
            ->emptyStateDescription('Quando alguém entrar em contato, aparecerá aqui.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'view' => Pages\ViewInquiry::route('/{record}'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
