import { type FormEvent, useEffect, useState } from 'react'
import type { ComplaintRecord } from '../../types/complaints'
import { getComplaintByTrackingId } from '../../utils/complaintsStorage'
import ActionButton from '../ui/ActionButton'
import InputField from '../ui/InputField'

type TrackComplaintSectionProps = {
  initialTrackingId?: string
  onFound: (record: ComplaintRecord) => void
  onInvalidId: () => void
  onNotFound: (trackingId: string) => void
}

function TrackComplaintSection({
  initialTrackingId = '',
  onFound,
  onInvalidId,
  onNotFound,
}: TrackComplaintSectionProps) {
  const [trackingId, setTrackingId] = useState<string>(initialTrackingId)
  const [isLoading, setIsLoading] = useState<boolean>(false)

  useEffect(() => {
    setTrackingId(initialTrackingId)
  }, [initialTrackingId])

  const onSubmit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault()
    const normalized = trackingId.trim()

    if (!normalized) {
      onInvalidId()
      return
    }

    setIsLoading(true)

    window.setTimeout(() => {
      const record = getComplaintByTrackingId(normalized)
      setIsLoading(false)
      if (!record) {
        onNotFound(normalized)
        return
      }
      onFound(record)
    }, 600)
  }

  return (
    <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
      <h1 className="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Track Complaint</h1>
      <p className="mt-2 max-w-2xl text-sm text-slate-600 md:text-base">
        Enter your tracking ID to check the latest complaint status.
      </p>

      <form className="mt-6 grid max-w-xl gap-4" noValidate onSubmit={onSubmit}>
        <InputField
          id="track-complaint-id"
          label="Tracking ID"
          value={trackingId}
          onChange={setTrackingId}
          required
          placeholder="BOCRA-YYYYMMDD-1234"
        />
        <div>
          <ActionButton isLoading={isLoading} type="submit">
            Check Status
          </ActionButton>
        </div>
      </form>
    </section>
  )
}

export default TrackComplaintSection
