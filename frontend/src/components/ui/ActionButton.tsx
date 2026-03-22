import type { ButtonHTMLAttributes, ReactNode } from 'react'

type ActionButtonVariant = 'primary' | 'secondary'

type ActionButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
  children: ReactNode
  isLoading?: boolean
  variant?: ActionButtonVariant
}

const variantClassMap: Record<ActionButtonVariant, string> = {
  primary:
    'bg-slate-900 text-white hover:bg-slate-800 focus-visible:ring-slate-500 disabled:bg-slate-500',
  secondary:
    'border border-slate-300 bg-white text-slate-800 hover:bg-slate-100 focus-visible:ring-slate-400',
}

function ActionButton({
  children,
  className = '',
  disabled,
  isLoading = false,
  type = 'button',
  variant = 'primary',
  ...rest
}: ActionButtonProps) {
  return (
    <button
      type={type}
      disabled={disabled || isLoading}
      className={`inline-flex min-h-11 items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed ${variantClassMap[variant]} ${className}`.trim()}
      {...rest}
    >
      {isLoading ? 'Please wait...' : children}
    </button>
  )
}

export default ActionButton
