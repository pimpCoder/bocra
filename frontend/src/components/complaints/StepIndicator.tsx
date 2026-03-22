import { FiCheck } from 'react-icons/fi'

type StepIndicatorProps = {
  steps: string[]
  currentStep: number
}

function StepIndicator({ steps, currentStep }: StepIndicatorProps) {
  return (
    <div aria-label="Complaint progress" className="w-full overflow-x-auto">
      <ol className="mx-auto flex min-w-[520px] items-start justify-between gap-2">
        {steps.map((step, index) => {
          const isComplete = index < currentStep
          const isCurrent = index === currentStep

          return (
            <li key={step} className="flex flex-1 items-start gap-2">
              <div className="flex w-full flex-col items-center text-center">
                <div
                  className={`flex h-9 w-9 items-center justify-center rounded-full border-2 text-sm font-semibold ${
                    isComplete
                      ? 'border-green-600 bg-green-600 text-white'
                      : isCurrent
                        ? 'border-blue-700 bg-blue-50 text-blue-700'
                        : 'border-slate-300 bg-white text-slate-500'
                  }`}
                  aria-current={isCurrent ? 'step' : undefined}
                >
                  {isComplete ? <FiCheck aria-hidden="true" /> : index + 1}
                </div>
                <p
                  className={`mt-2 text-xs font-medium md:text-sm ${
                    isCurrent ? 'text-blue-700' : 'text-slate-600'
                  }`}
                >
                  {step}
                </p>
              </div>

              {index < steps.length - 1 ? (
                <div
                  aria-hidden="true"
                  className={`mt-4 h-0.5 w-full ${
                    isComplete ? 'bg-green-600' : 'bg-slate-300'
                  }`}
                />
              ) : null}
            </li>
          )
        })}
      </ol>
    </div>
  )
}

export default StepIndicator
