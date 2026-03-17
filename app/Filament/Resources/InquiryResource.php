<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $modelLabel = 'Mensagem';

    protected static ?string $pluralModelLabel = 'Mensagens';

    protected static ?string $navigationLabel = 'Mensagens';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([

                        // ─── Coluna principal (mensagem em destaque) ───────────────
                        Forms\Components\Group::make()
                            ->schema([

                                Forms\Components\Section::make('Mensagem recebida')
                                    ->description('Conteúdo enviado pelo lead via formulário de contato.')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                    ->schema([
                                        Forms\Components\Textarea::make('message')
                                            ->label('')
                                            ->rows(12)
                                            ->disabled()
                                            ->columnSpanFull(),
                                    ]),

                            ])
                            ->columnSpan(2),

                        // ─── Sidebar direita ───────────────────────────────────────
                        Forms\Components\Group::make()
                            ->schema([

                                Forms\Components\Section::make('Contato')
                                    ->icon('heroicon-o-user-circle')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nome')
                                            ->disabled(),

                                        Forms\Components\TextInput::make('email')
                                            ->label('E-mail')
                                            ->disabled(),

                                        Forms\Components\TextInput::make('whatsapp')
                                            ->label('WhatsApp')
                                            ->disabled(),
                                    ]),

                                // ── Campos futuros: gestão do lead ────────────────
                                Forms\Components\Section::make('Gestão')
                                    ->icon('heroicon-o-adjustments-horizontal')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'new' => 'Novo',
                                                'in_progress' => 'Em atendimento',
                                                'resolved' => 'Resolvido',
                                                'archived' => 'Arquivado',
                                            ])
                                            ->default('new')
                                            ->native(false),

                                        Forms\Components\Textarea::make('notes')
                                            ->label('Anotações internas')
                                            ->placeholder('Observações sobre este contato...')
                                            ->rows(4),
                                    ]),

                                // ── Timestamps ────────────────────────────────────
                                Forms\Components\Section::make('Registro')
                                    ->icon('heroicon-o-clock')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\Placeholder::make('created_at')
                                            ->label('Recebido em')
                                            ->content(
                                                fn ($record) => $record?->created_at
                                                    ?->setTimezone('America/Sao_Paulo')
                                                    ->format('d/m/Y \à\s H:i')
                                                    ?? '—'
                                            ),

                                        Forms\Components\Placeholder::make('updated_at')
                                            ->label('Última atualização')
                                            ->content(
                                                fn ($record) => $record?->updated_at
                                                    ?->setTimezone('America/Sao_Paulo')
                                                    ->format('d/m/Y \à\s H:i')
                                                    ?? '—'
                                            ),
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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->copyable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->message),

                // ── Campo futuro já exposto na listagem ──────────────────
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
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
                    ->label('Recebido em')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('America/Sao_Paulo')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Novo',
                        'in_progress' => 'Em atendimento',
                        'resolved' => 'Resolvido',
                        'archived' => 'Arquivado',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->label('Período')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('De'),
                        Forms\Components\DatePicker::make('to')->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn (Builder $q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['to'], fn (Builder $q) => $q->whereDate('created_at', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('whatsapp')
                        ->label('WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn (Inquiry $record): string => 'https://wa.me/'.preg_replace('/\D/', '', $record->whatsapp))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('email')
                        ->label('E-mail')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->url(fn (Inquiry $record): string => "mailto:{$record->email}")
                        ->openUrlInNewTab(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
