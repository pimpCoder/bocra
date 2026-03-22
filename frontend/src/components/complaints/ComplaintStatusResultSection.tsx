import type { ComplaintRecord, ComplaintStatus } from '../../types/complaints'
import StepIndicator from './StepIndicator'

const statusSteps: ComplaintStatus[] = ['Submitted', 'In Review', 'Resolved']

const formatSubmittedDate = (isoDate: string) =>
  new Intl.DateTimeFormat('en-BW', {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(new Date(isoDate))

type ComplaintStatusResultSectionProps = {
  record: ComplaintRecord
}

function ComplaintStatusResultSection({ record }: ComplaintStatusResultSectionProps) {
  const currentStep = Math.max(statusSteps.indexOf(record.status), 0)

  return (
    <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
      <h1 className="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">
        Complaint Status Result
      </h1>

      <div className="mt-6 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2">
        <p className="text-sm text-slate-700">
          <span className="font-semibold text-slate-900">Tracking ID:</span> {record.trackingId}
        </p>
        <p className="text-sm text-slate-700">
          <span className="font-semibold text-slate-900">Category:</span> {record.category}
        </p>
        <p className="text-sm text-slate-700">
          <span className="font-semibold text-slate-900">Date Submitted:</span>{' '}
          {formatSubmittedDate(record.submittedAt)}
        </p>
        <p className="text-sm text-slate-700">
          <span className="font-semibold text-slate-900">Current Status:</span> {record.status}
        </p>
      </div>

      <div className="mt-8">
        <StepIndicator
          currentStep={currentStep}
          steps={['Submitted', 'In Review', 'Resolved']}
        />
      </div>

      <p className="mt-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        {record.statusMessage}
      </p>
    </section>
  )
}

export default ComplaintStatusResultSection
