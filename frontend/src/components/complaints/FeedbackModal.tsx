import { useEffect, useRef, useState } from 'react'
import { FiAlertCircle, FiCheckCircle } from 'react-icons/fi'
import ActionButton from '../ui/ActionButton'

type FeedbackTone = 'success' | 'error'

type FeedbackModalProps = {
  isOpen: boolean
  onClose: () => void
  title: string
  message: string
  tone?: FeedbackTone
}

const ANIMATION_MS = 180

function FeedbackModal({
  isOpen,
  onClose,
  title,
  message,
  tone = 'success',
}: FeedbackModalProps) {
  const dialogRef = useRef<HTMLDivElement | null>(null)
  const [isMounted, setIsMounted] = useState<boolean>(isOpen)
  const [isVisible, setIsVisible] = useState<boolean>(isOpen)

  useEffect(() => {
    if (isOpen) {
      setIsMounted(true)
      const raf = requestAnimationFrame(() => setIsVisible(true))
      return () => cancelAnimationFrame(raf)
    }

    setIsVisible(false)
    const timeout = window.setTimeout(() => setIsMounted(false), ANIMATION_MS)
    return () => window.clearTimeout(timeout)
  }, [isOpen])

  useEffect(() => {
    if (!isOpen) return

    const previouslyFocused = document.activeElement as HTMLElement | null
    const selector = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'

    const focusFirst = () => {
      const nodes = dialogRef.current?.querySelectorAll<HTMLElement>(selector)
      if (nodes && nodes.length > 0) nodes[0].focus()
    }

    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        event.preventDefault()
        onClose()
        return
      }
      if (event.key !== 'Tab') return

      const nodes = dialogRef.current?.querySelectorAll<HTMLElement>(selector)
      if (!nodes || nodes.length === 0) return
      const first = nodes[0]
      const last = nodes[nodes.length - 1]
      const active = document.activeElement as HTMLElement | null

      if (event.shiftKey && active === first) {
        event.preventDefault()
        last.focus()
      } else if (!event.shiftKey && active === last) {
        event.preventDefault()
        first.focus()
      }
    }

    focusFirst()
    document.addEventListener('keydown', onKeyDown)
    return () => {
      document.removeEventListener('keydown', onKeyDown)
      previouslyFocused?.focus()
    }
  }, [isOpen, onClose])

  if (!isMounted) return null

  const Icon = tone === 'success' ? FiCheckCircle : FiAlertCircle
  const accentClass = tone === 'success' ? 'text-green-600' : 'text-red-600'

  return (
    <div
      className={`fixed inset-0 z-50 flex items-center justify-center px-4 transition-opacity duration-200 ${
        isVisible ? 'bg-slate-900/45 opacity-100' : 'bg-slate-900/0 opacity-0'
      }`}
      onMouseDown={(event) => {
        if (event.target === event.currentTarget) onClose()
      }}
      role="presentation"
    >
      <div
        ref={dialogRef}
        role="dialog"
        aria-modal="true"
        aria-labelledby="feedback-modal-title"
        className={`w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all duration-200 ${
          isVisible ? 'scale-100 opacity-100' : 'scale-95 opacity-0'
        }`}
      >
        <div className="flex items-start gap-3">
          <Icon aria-hidden="true" className={`mt-0.5 h-7 w-7 ${accentClass}`} />
          <div>
            <h2 id="feedback-modal-title" className="text-xl font-bold text-slate-900">
              {title}
            </h2>
            <p className="mt-2 text-sm text-slate-600">{message}</p>
          </div>
        </div>
        <div className="mt-6">
          <ActionButton onClick={onClose} variant="secondary">
            Close
          </ActionButton>
        </div>
      </div>
    </div>
  )
}

export default FeedbackModal
