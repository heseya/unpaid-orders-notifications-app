const formattersMap = new Map()

export const formatCurrency = (value: number, currency: string = 'PLN') => {
  const formatter =
    formattersMap.get(currency) ||
    new Intl.NumberFormat('pl-PL', {
      style: 'currency',
      currency,
      minimumFractionDigits: 2,
    })

  formattersMap.set(currency, formatter)

  return formatter.format(value)
}
