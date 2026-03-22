import type { ReactNode } from 'react'

type Option = {
  label: string
  value: string
}

type BaseProps = {
  id: string
  label: string
  required?: boolean
  error?: string
  hint?: string
  className?: string
}

type TextInputProps = BaseProps & {
  as?: 'input'
  inputType?: 'text' | 'email' | 'tel'
  value: string
  onChange: (value: string) => void
  placeholder?: string
}

type SelectInputProps = BaseProps & {
  as: 'select'
  value: string
  onChange: (value: string) => void
  options: Option[]
  placeholder?: string
}

type TextareaProps = BaseProps & {
  as: 'textarea'
  value: string
  onChange: (value: string) => void
  rows?: number
  placeholder?: string
}

type InputFieldProps = TextInputProps | SelectInputProps | TextareaProps

const baseControlClass =
  'w-full rounded-lg border px-3 py-2 text-sm text-slate-900 transition focus-visible:outline-none focus-visible:ring-2'

function FieldNote({ children, id, isError }: { children: ReactNode; id?: string; isError?: boolean }) {
  return (
    <p
      id={id}
      className={`text-xs ${isError ? 'text-red-700' : 'text-slate-600'}`}
      role={isError ? 'alert' : undefined}
    >
      {children}
    </p>
  )
}

function InputField(props: InputFieldProps) {
  const describedBy = `${props.id}-help`
  const hasError = Boolean(props.error)
  const controlClass = `${baseControlClass} ${
    hasError
      ? 'border-red-400 bg-red-50 focus-visible:ring-red-300'
      : 'border-slate-300 bg-white focus-visible:ring-slate-300'
  }`

  return (
    <div className={`grid gap-1.5 ${props.className ?? ''}`.trim()}>
      <label className="text-sm font-medium text-slate-900" htmlFor={props.id}>
        {props.label}
        {props.required ? <span className="ml-1 text-red-700">*</span> : null}
      </label>

      {props.as === 'textarea' ? (
        <textarea
          id={props.id}
          required={props.required}
          value={props.value}
          onChange={(event) => props.onChange(event.target.value)}
          rows={props.rows ?? 5}
          placeholder={props.placeholder}
          aria-invalid={hasError}
          aria-describedby={props.error || props.hint ? describedBy : undefined}
          className={controlClass}
        />
      ) : null}

      {props.as === 'select' ? (
        <select
          id={props.id}
          required={props.required}
          value={props.value}
          onChange={(event) => props.onChange(event.target.value)}
          aria-invalid={hasError}
          aria-describedby={props.error || props.hint ? describedBy : undefined}
          className={controlClass}
        >
          <option value="">{props.placeholder ?? 'Select an option'}</option>
          {props.options.map((option) => (
            <option key={option.value} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>
      ) : null}

      {(props.as === 'input' || props.as === undefined) && (
        <input
          id={props.id}
          required={props.required}
          type={props.inputType ?? 'text'}
          value={props.value}
          onChange={(event) => props.onChange(event.target.value)}
          placeholder={props.placeholder}
          aria-invalid={hasError}
          aria-describedby={props.error || props.hint ? describedBy : undefined}
          className={controlClass}
        />
      )}

      {props.error ? <FieldNote id={describedBy} isError>{props.error}</FieldNote> : null}
      {!props.error && props.hint ? <FieldNote id={describedBy}>{props.hint}</FieldNote> : null}
    </div>
  )
}

export default InputField
